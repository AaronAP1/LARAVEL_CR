<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Sales_Details;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
   
    public function getTopSellingProducts(Request $request)
    {
        // Filtros opcionales
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Obtener productos más vendidos en el rango de fechas
        $topProducts = Sales_Details::selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total_price) as total_sales')
            ->with('product') // Asegúrate de que la relación esté definida en el modelo SalesDetail
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate);
                }
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sales') // Ordenar por monto total de ventas
            ->limit(20) // Limitar a los 20 productos más vendidos
            ->get();
        
        // Preparar los datos para la respuesta
        $responseData = $topProducts->map(function ($item) {
            return [
                'SKU' => $item->product->sku,
                'Nombre del producto' => $item->product->name,
                'Cantidad total vendida' => $item->total_quantity,
                'Monto total de ventas' => $item->total_sales,
            ];
        });

        return response()->json([
            'data' => $responseData,
        ]);
    }

    public function getSalesReport(Request $request)
    {
       
    
        // Filtros opcionales
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $clientId = $request->input('client_id');
        $sellerId = $request->input('seller_id');
        $timeRange = $request->input('time_range', 'daily'); // Puede ser daily, weekly o monthly
    
        // Filtro por cliente y vendedor si se proporcionan
        $salesQuery = Sales::select('sales.id', 'clients.name as client_name', 'clients.identification as client_id', 
                            'clients.email as client_email', 'sales.created_at as sale_date')
    ->join('clients', 'sales.client_id', '=', 'clients.id')
    ->join('sales__details', 'sales.id', '=', 'sales__details.sale_id')
    ->with(['salesDetails.product'])
    ->where('sales.deleted_at', null)
    ->where('sales.client_id', $clientId)
    ->where('sales.seller_id', $sellerId);// Filtrar por vendedor
            
        // Filtros adicionales
        if ($clientId) {
            $salesQuery->where('sale.client_id', $clientId); // Filtrar por cliente
        }
        if ($sellerId) {
            $salesQuery->where('sale.seller_id', $sellerId); // Filtrar por vendedor
        }
        if ($startDate) {
            $salesQuery->where('sale.created_at', '>=', $startDate); // Filtrar por fecha de inicio
        }
        if ($endDate) {
            $salesQuery->where('sale.created_at', '<=', $endDate); // Filtrar por fecha de fin
        }
    
        // Agrupar las ventas según el rango de tiempo solicitado
        switch ($timeRange) {
            case 'weekly':
                $salesQuery->selectRaw('WEEK(sale.created_at) as week, YEAR(sale.created_at) as year');
                break;
            case 'monthly':
                $salesQuery->selectRaw('MONTH(sale.created_at) as month, YEAR(sale.created_at) as year');
                break;
            case 'daily':
            default:
                $salesQuery->selectRaw('DATE(sale.created_at) as sale_date');
                break;
        }
    
        // Obtener las ventas agrupadas
        $sales = $salesQuery->get();
    
        // Preparar los datos para la respuesta
        $responseData = $sales->map(function ($sale) use ($timeRange) {
            $totalQuantity = $sale->salesDetails->sum('quantity');
            $totalAmount = $sale->salesDetails->sum('total_price');
            
            return [
                'Código' => $sale->id,
                'Nombre cliente' => $sale->client_name,
                'Identificación cliente' => $sale->client_id,
                'Correo cliente' => $sale->client_email,
                'Cantidad productos' => $totalQuantity,
                'Monto total' => $totalAmount,
                'Fecha y hora' => $sale->sale_date,
            ];
        });
    
        // Retornar los datos en formato JSON
        return response()->json([
            'data' => $responseData,
        ]);
    }
    


}
