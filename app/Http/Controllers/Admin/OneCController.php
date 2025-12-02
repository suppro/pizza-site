<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OneCService;
use Illuminate\Http\Request;

class OneCController extends Controller
{
    protected $oneCService;

    public function __construct(OneCService $oneCService)
    {
        $this->oneCService = $oneCService;
    }

    /**
     * Показать страницу управления интеграцией с 1С
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $pendingOrders = Order::where('is_synced_to_1c', false)
            ->whereIn('status', ['new', 'processing'])
            ->count();

        $syncedOrders = Order::where('is_synced_to_1c', true)->count();
        $totalOrders = Order::count();

        $isEnabled = config('services.onec.enabled', false);

        return view('admin.onec.index', compact(
            'pendingOrders',
            'syncedOrders',
            'totalOrders',
            'isEnabled'
        ));
    }

    /**
     * Выгрузить конкретный заказ в 1С
     */
    public function exportOrder(Request $request, $orderId)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $order = Order::with('items')->findOrFail($orderId);

        if ($order->is_synced_to_1c) {
            return back()->with('info', 'Заказ уже выгружен в 1С');
        }

        if ($this->oneCService->exportOrder($order)) {
            return back()->with('success', 'Заказ успешно выгружен в 1С');
        } else {
            return back()->with('error', 'Не удалось выгрузить заказ в 1С. Проверьте логи и настройки.');
        }
    }

    /**
     * Выгрузить все невыгруженные заказы
     */
    public function exportAll(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            return redirect()->route('login');
        }

        $results = $this->oneCService->exportPendingOrders();

        if ($results['success'] > 0) {
            $message = "Успешно выгружено заказов: {$results['success']}";
            if ($results['failed'] > 0) {
                $message .= ". Ошибок: {$results['failed']}";
            }
            return back()->with('success', $message);
        } else {
            return back()->with('error', 'Не удалось выгрузить заказы. Проверьте логи и настройки.');
        }
    }
}

