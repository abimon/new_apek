<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Shop::simplePaginate(10);
        return view('admin.shop.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories =['Books','Bibles','Hymnals','Devotionals','Gifts','Other'];
        return view('admin.shop.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255|unique:shops,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'status' => 'nullable|in:available,unavailable,discontinued',
        ]);
        $validated['image_url'] = request()->file('image')->store('products', 'public');
        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        $validated['createdby'] = Auth::user()->id;
        Shop::create($validated);

        return redirect()->route('admin.shop.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $product)
    {
        return view('admin.shop.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $product)
    {
        return view('admin.shop.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shops,name,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'status' => 'required|in:available,unavailable,discontinued',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        if($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('products', 'public');
        }
        $product->update($validated);

        return redirect()->route('admin.shop.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $shop->delete();

        return redirect()->route('admin.shop.index')->with('success', 'Product deleted successfully.');
    }
}
