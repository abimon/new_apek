<?php

namespace App\Http\Controllers;

use App\Models\Kamande;
use Illuminate\Http\Request;

class KamandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => Kamande::all()
        ]);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:kamandes,email',
            'phone' => 'required|string|max:20|unique:kamandes,phone',
            'message' => 'required|string',
            'status' => 'nullable|boolean',
        ]);
        if (!$validated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed'
            ], 422);
        }
        $kamande = Kamande::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $kamande
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kamande $kamande)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kamande $kamande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kamande $kamande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kamande $kamande)
    {
        //
    }
}
