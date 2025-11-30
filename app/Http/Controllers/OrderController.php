<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['variant']->price * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        $request->validate([
            'delivery_address' => 'required|string|max:200',
            'comment' => 'nullable|string|max:500'
        ]);

        // Создаем заказ
        $order = Order::create([
            'user_id' => session('user_id'),
            'status_id' => 1, // Новый заказ
            'delivery_address' => $request->delivery_address,
            'comment' => $request->comment,
            'total_price' => 0 // Посчитаем ниже
        ]);

        // Добавляем товары в заказ
        $totalPrice = 0;
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item['variant']->id,
                'quantity' => $item['quantity'],
                'price_at_moment' => $item['variant']->price
            ]);
            $totalPrice += $item['variant']->price * $item['quantity'];
        }

        // Обновляем общую сумму заказа
        $order->update(['total_price' => $totalPrice]);

        // Очищаем корзину
        session()->forget('cart');

        return redirect()->route('orders')->with('success', 'Заказ успешно создан! Номер вашего заказа: #' . $order->id);
    }

    public function index()
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', session('user_id'))
                      ->with('status')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('orders', compact('orders'));
    }
}