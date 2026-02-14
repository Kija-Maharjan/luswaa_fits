<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product.user')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.08; // 8% tax
        $shipping = $subtotal > 50 ? 0 : 5.99; // Free shipping over $50
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check if product is available
        if ($product->status !== 'available') {
            return back()->with('error', 'This product is no longer available.');
        }

        // Check if user is trying to add their own product
        if ($product->user_id === Auth::id()) {
            return back()->with('error', 'You cannot add your own product to cart.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + ($validated['quantity'] ?? 1)
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'] ?? 1,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'subtotal' => $cartItem->subtotal,
        ]);
    }

    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Cart cleared successfully.');
    }
}
