<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (!session('user_id')) {
        return redirect()->route('login');
    }
        $cart = session('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $variant = ProductVariant::findOrFail($request->variant_id);
        $cart = session('cart', []);

        if (isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity']++;
        } else {
            $cart[$variant->id] = [
                'variant' => $variant,
                'quantity' => 1
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Добавлено в корзину!');
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = max(1, $request->quantity);
        }
        session(['cart' => $cart]);
        return back();
    }
    public function clear()
    {
    if (!session('user_id')) {
        return redirect()->route('login');
    }

    session()->forget('cart');
    
    return redirect()->route('cart')->with('success', 'Корзина очищена');
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Удалено из корзины');
    }

    
}