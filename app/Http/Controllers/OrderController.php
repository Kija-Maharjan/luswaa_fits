<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = Cart::with('product.user')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.08;
        $shipping = $subtotal > 50 ? 0 : 5.99;
        $total = $subtotal + $tax + $shipping;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_zip' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.08;
        $shipping = $subtotal > 50 ? 0 : 5.99;
        $total = $subtotal + $tax + $shipping;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip' => $validated['shipping_zip'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items and update product status
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'seller_id' => $cartItem->product->user_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);

                // Update product status to pending
                $cartItem->product->update(['status' => 'pending']);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product.user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function sales()
    {
        $sales = OrderItem::with('order.user', 'product')
            ->where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.sales', compact('sales'));
    }
}
