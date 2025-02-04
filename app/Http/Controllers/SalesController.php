<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales_Details;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleReceiptMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sales::all();

        return $sales;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            // Crear venta
            $sale = Sales::create([
                'code' => uniqid('SALE-'),
                'client_id' => $request->client_id,
                'seller_id' => $request->seller_id,
                'total_amount' => 0,
                'sale_datetime' => now()
            ]);

            foreach ($request->products as $productData) {
                $product = Products::findOrFail($productData['product_id']);

                if ($product->stock < $productData['quantity']) {
                    return response()->json(['error' => 'Stock insuficiente para el producto ' . $product->name], 400);
                }

                // Registrar detalle de venta
                $totalPrice = $productData['quantity'] * $productData['unit_price'];
                Sales_Details::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'total_price' => $totalPrice
                ]);

                // Actualizar stock del producto
                $product->decrement('stock', $productData['quantity']);

                // Calcular el total de la venta
                $totalAmount += $totalPrice;
            }

            // Actualizar total de la venta
            $sale->update(['total_amount' => $totalAmount]);

            DB::commit();

            $sale->load('details.product');

            // Enviar correo al cliente (Opcionalmente en cola)
            $client = User::findOrFail($request->client_id);
            Mail::to($client->email)->send(new SaleReceiptMail($sale));

            return response()->json(['message' => 'Venta registrada con éxito', 'sale' => $sale], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al registrar la venta', 'details' => $e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sale = Sales::with('client', 'details.product') 
                    ->find($id);

        // Verificar si la venta fue encontrada
        if (!$sale) {
            return response()->json([
                'error' => 'Venta no encontrada'
            ], 404);
        }

        // Retornar los detalles de la venta
        return response()->json([
            'venta' => [
                'id' => $sale->id,
                'codigo' => $sale->code,
                'fecha' => $sale->sale_datetime,
                'total' => $sale->total_amount,
                'cliente' => [
                    'nombre' => $sale->client->name,
                    'email' => $sale->client->email,
                    'telefono' => $sale->client->phone,
                ],
                'productos' => $sale->details->map(function($detail) {
                    return [
                        'producto' => $detail->product->name,
                        'cantidad' => $detail->quantity,
                        'precio_unitario' => $detail->product->price,
                        'total' => $detail->total_price,
                    ];
                }),
            ]
        ]);
    }

    public function detail($id)
    {
        $sale = Sales::with(['saleDetails.product', 'client'])->find($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        // Resumen de la venta
        $saleDetail = $sale->saleDetails->map(function ($detail) {
            return [
                'product_name' => $detail->product->name,
                'quantity' => $detail->quantity,
                'unit_price' => $detail->unit_price,
                'total_price' => $detail->total_price,
            ];
        });

        $saleData = [
            'sale_code' => $sale->code,
            'sale_datetime' => $sale->sale_datetime,
            'total_amount' => $sale->total_amount,
            'client_name' => $sale->client->name,
            'sale_details' => $saleDetail,
        ];

        return response()->json($saleData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
