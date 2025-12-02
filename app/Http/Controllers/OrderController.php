<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        // Редирект на первый шаг
        return redirect()->route('checkout.step1');
    }

    public function step1()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        // Проверяем наличие товаров и пересчитываем сумму
        $total = 0;
        foreach ($cart as $item) {
            $product = Product::find($item['product']->id);
            if (!$product || $product->stock_quantity < $item['quantity']) {
                return redirect()->route('cart')->with('error', 'Товар "' . $item['product']->name . '" недоступен в нужном количестве');
            }
            $total += $product->price * $item['quantity'];
        }

        return view('checkout-step1', compact('cart', 'total'));
    }

    public function step2(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->price * $item['quantity'];
        }

        // Сохраняем данные клиента в сессии
        session(['checkout.customer' => [
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->customer_email,
        ]]);

        return view('checkout-step2', [
            'cart' => $cart,
            'total' => $total,
            'customerData' => session('checkout.customer')
        ]);
    }

    public function step3(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'delivery_method' => 'required|in:pickup,courier,transport,express_delivery,standard_delivery',
            'payment_method' => 'required|in:cash,card,transfer,cash_on_delivery,credit_card,bank_transfer',
            'comment' => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);
        $customerData = session('checkout.customer');
        
        if (empty($cart) || empty($customerData)) {
            return redirect()->route('checkout.step1')->with('error', 'Сессия истекла. Начните оформление заново.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->price * $item['quantity'];
        }

        // Сохраняем данные доставки в сессии
        session(['checkout.delivery' => [
            'address' => $request->delivery_address,
            'method' => $request->delivery_method,
            'payment' => $request->payment_method,
            'comment' => $request->comment,
        ]]);

        return view('checkout-step3', [
            'cart' => $cart,
            'total' => $total,
            'customerData' => $customerData,
            'deliveryData' => session('checkout.delivery')
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        // Получаем данные из сессии (валидация уже выполнена на шагах 1-3)
        $customerData = session('checkout.customer');
        $deliveryData = session('checkout.delivery');
        
        if (empty($customerData) || empty($deliveryData)) {
            return redirect()->route('checkout.step1')->with('error', 'Сессия истекла. Начните оформление заново.');
        }
        
        // Нормализуем значения для сохранения в новом формате
        $paymentMethod = \App\Helpers\OrderHelper::normalizePaymentMethod($deliveryData['payment']);
        $deliveryMethod = \App\Helpers\OrderHelper::normalizeDeliveryMethod($deliveryData['method']);

        // Проверяем наличие товаров и считаем сумму
        $totalAmount = 0;
        $itemsToCreate = [];
        
        foreach ($cart as $item) {
            $product = Product::find($item['product']->id);
            if (!$product || !$product->is_active) {
                return redirect()->route('cart')->with('error', 'Товар "' . $item['product']->name . '" недоступен');
            }
            if ($product->stock_quantity < $item['quantity']) {
                return redirect()->route('cart')->with('error', 'Недостаточно товара "' . $product->name . '" на складе. Доступно: ' . $product->stock_quantity);
            }
            
            $itemTotal = $product->price * $item['quantity'];
            $totalAmount += $itemTotal;
            
            $itemsToCreate[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price_at_purchase' => $product->price,
                'quantity' => $item['quantity']
            ];
        }


        // Формируем customer_details
        $customerDetails = [
            'name' => $customerData['name'],
            'phone' => $customerData['phone'],
            'address' => $deliveryData['address'],
        ];
        
        if (!empty($customerData['email'])) {
            $customerDetails['email'] = $customerData['email'];
        }
        
        if (!empty($deliveryData['comment'])) {
            $customerDetails['comment'] = $deliveryData['comment'];
        }

        // Создаем заказ
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'new',
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'delivery_method' => $deliveryMethod,
            'customer_details' => $customerDetails,
            'is_synced_to_1c' => false
        ]);

        // Добавляем товары в заказ и уменьшаем остатки
        foreach ($itemsToCreate as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $itemData['product_id'],
                'product_name' => $itemData['product_name'],
                'product_sku' => $itemData['product_sku'],
                'price_at_purchase' => $itemData['price_at_purchase'],
                'quantity' => $itemData['quantity']
            ]);
            
            // Уменьшаем остаток на складе
            $product = Product::find($itemData['product_id']);
            $product->decrement('stock_quantity', $itemData['quantity']);
        }

        // Очищаем корзину и данные оформления
        session()->forget('cart');
        session()->forget('checkout.customer');
        session()->forget('checkout.delivery');

        return redirect()->route('orders')->with('success', 'Заказ успешно создан! Номер вашего заказа: #' . $order->id);
    }

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', auth()->id())
                      ->with('items')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('orders', compact('orders'));
    }
}