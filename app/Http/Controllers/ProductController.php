<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('user')->available();

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'ILIKE', '%' . $request->search . '%')
                  ->orWhere('brand', 'ILIKE', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition', $request->condition);
        }

        // Price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('user')->findOrFail($id);
        $product->incrementViews();

        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->available()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string',
            'brand' => 'nullable|string',
            'condition' => 'required|in:new,like_new,good,fair',
            'category' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $product = Auth::user()->products()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'size' => $validated['size'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'condition' => $validated['condition'],
            'category' => $validated['category'],
            'images' => $images,
            'status' => 'available',
        ]);

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Product listed successfully!');
    }

    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string',
            'brand' => 'nullable|string',
            'condition' => 'required|in:new,like_new,good,fair',
            'category' => 'required|string',
            'status' => 'required|in:available,sold,pending',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
        }

        $product->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'size' => $validated['size'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'condition' => $validated['condition'],
            'category' => $validated['category'],
            'status' => $validated['status'],
            'images' => $images,
        ]);

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('profile.listings')
            ->with('success', 'Product deleted successfully!');
    }
}
