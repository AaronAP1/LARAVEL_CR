<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Clients::all();

        return $clients;
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
            'name' => 'required|string|max:255',
            'identification' => 'required|string|unique:clients,identification|max:50',
            'email' => 'required|email|unique:clients,email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $client = Clients::create($request->all());

        return response()->json(['message' => 'Cliente creado con éxito', 'client' => $client], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Clients::find($id);

        if ($client) {
            return $client;
        } else {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $clients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Clients::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'identification' => 'sometimes|string|unique:clients,identification,' . $client->id . '|max:50',
            'email' => 'sometimes|email|unique:clients,email,' . $client->id . '|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);

        $client->update($request->all());

        return response()->json(['message' => 'Cliente actualizado con éxito', 'client' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Clients::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Cliente eliminado con éxito']);
    }
}
