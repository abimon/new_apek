<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosStock extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
return request();
        if (request()->hasFile('image')) {
            $filepath = request()->file('image')->store('pos/stock', 'public');
        }
        if (request()->hasFile('licence')) {
            $filepath = request()->file('licence')->store('pos/licenses', 'public');
        } else {
            $filepath = '';
        }
        if ($filepath != '') {
            return response()->json([
                'filepath' => 'https://apektechinc.com/storage/' . $filepath
            ], 201);
        } else {
            return response()->json([
                'error' => 'No file attached'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
