<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneCService
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $enabled;

    public function __construct()
    {
        $this->baseUrl = config('services.onec.base_url');
        $this->username = config('services.onec.username');
        $this->password = config('services.onec.password');
        $this->enabled = config('services.onec.enabled', false);
    }

    /**
     * Выгрузить заказ в 1С
     *
     * @param Order $order
     * @return bool
     */
    public function exportOrder(Order $order): bool
    {
        if (!$this->enabled) {
            Log::info('1C integration is disabled', ['order_id' => $order->id]);
            return false;
        }

        if ($order->is_synced_to_1c) {
            Log::info('Order already synced to 1C', ['order_id' => $order->id]);
            return true;
        }

        try {
            $orderData = $this->formatOrderFor1C($order);

            $response = Http::timeout(30)
                ->withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/api/orders', $orderData);

            if ($response->successful()) {
                $order->update(['is_synced_to_1c' => true]);
                Log::info('Order successfully exported to 1C', [
                    'order_id' => $order->id,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to export order to 1C', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception while exporting order to 1C', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Форматировать заказ для выгрузки в 1С
     *
     * @param Order $order
     * @return array
     */
    protected function formatOrderFor1C(Order $order): array
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'sku' => $item->product_sku,
                'name' => $item->product_name,
                'quantity' => $item->quantity,
                'price' => (float) $item->price_at_purchase,
                'total' => (float) ($item->price_at_purchase * $item->quantity),
            ];
        }

        return [
            'order_id' => $order->id,
            'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'date' => $order->created_at->format('Y-m-d\TH:i:s'),
            'status' => $this->mapStatusTo1C($order->status),
            'total_amount' => (float) $order->total_amount,
            'payment_method' => $this->mapPaymentMethodTo1C($order->payment_method),
            'delivery_method' => $this->mapDeliveryMethodTo1C($order->delivery_method),
            'customer' => [
                'name' => $order->customer_name,
                'phone' => $order->customer_phone ?? '',
                'email' => $order->customer_details['email'] ?? ($order->user->email ?? ''),
                'address' => $order->delivery_address ?? '',
            ],
            'items' => $items,
            'comment' => $order->customer_details['comment'] ?? '',
        ];
    }

    /**
     * Маппинг статуса заказа для 1С
     */
    protected function mapStatusTo1C(string $status): string
    {
        $map = [
            'new' => 'Новый',
            'processing' => 'ВОбработке',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменен',
        ];

        return $map[$status] ?? $status;
    }

    /**
     * Маппинг способа оплаты для 1С
     */
    protected function mapPaymentMethodTo1C(string $method): string
    {
        $map = [
            'cash' => 'Наличные',
            'card' => 'БанковскаяКарта',
            'transfer' => 'БанковскийПеревод',
        ];

        return $map[$method] ?? $method;
    }

    /**
     * Маппинг способа доставки для 1С
     */
    protected function mapDeliveryMethodTo1C(string $method): string
    {
        $map = [
            'pickup' => 'Самовывоз',
            'courier' => 'Курьер',
            'transport' => 'ТранспортнаяКомпания',
        ];

        return $map[$method] ?? $method;
    }

    /**
     * Выгрузить все невыгруженные заказы
     *
     * @return array
     */
    public function exportPendingOrders(): array
    {
        $orders = Order::where('is_synced_to_1c', false)
            ->whereIn('status', ['new', 'processing'])
            ->with('items')
            ->get();

        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($orders as $order) {
            if ($this->exportOrder($order)) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['errors'][] = "Заказ #{$order->id} не удалось выгрузить";
            }
        }

        return $results;
    }
}

