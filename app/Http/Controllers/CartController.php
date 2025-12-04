<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())
                    ->latest()
                    ->first();
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'items' => [],
                'total_amount' => 0,
                'session_id' => session()->getId(),
                'last_activity_at' => now(),
            ]
        );

        $items = $cart->items ?? [];
        
        // Check if item exists
        $found = false;
        foreach ($items as &$item) {
            if ($item['product_id'] == $validated['product_id']) {
                $item['quantity'] += $validated['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = $validated;
        }

        $total = collect($items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $cart->update([
            'items' => $items,
            'total_amount' => $total,
            'last_activity_at' => now(),
        ]);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart) {
            return redirect()->back();
        }

        $items = $cart->items;
        $productId = $request->product_id;
        $quantity = (int) $request->quantity;

        foreach ($items as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }

        $total = collect($items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $cart->update([
            'items' => $items,
            'total_amount' => $total,
            'last_activity_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($productId)
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart) {
            return redirect()->back();
        }

        $items = collect($cart->items)->filter(function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        })->values()->toArray();

        $total = collect($items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $cart->update([
            'items' => $items,
            'total_amount' => $total,
            'last_activity_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Item removed!');
    }
}