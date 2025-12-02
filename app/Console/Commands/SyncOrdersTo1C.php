<?php

namespace App\Console\Commands;

use App\Services\OneCService;
use Illuminate\Console\Command;

class SyncOrdersTo1C extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync-orders {--order-id= : Sync specific order by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизировать заказы с системой 1С';

    /**
     * Execute the console command.
     */
    public function handle(OneCService $oneCService)
    {
        if (!config('services.onec.enabled', false)) {
            $this->error('Интеграция с 1С отключена. Проверьте настройки в .env');
            return 1;
        }

        if ($orderId = $this->option('order-id')) {
            $order = \App\Models\Order::with('items')->find($orderId);
            
            if (!$order) {
                $this->error("Заказ #{$orderId} не найден");
                return 1;
            }

            $this->info("Выгрузка заказа #{$orderId}...");
            
            if ($oneCService->exportOrder($order)) {
                $this->info("✓ Заказ #{$orderId} успешно выгружен в 1С");
                return 0;
            } else {
                $this->error("✗ Не удалось выгрузить заказ #{$orderId}");
                return 1;
            }
        }

        $this->info('Начинаю синхронизацию заказов с 1С...');
        
        $results = $oneCService->exportPendingOrders();
        
        $this->info("Выгружено успешно: {$results['success']}");
        $this->warn("Ошибок: {$results['failed']}");
        
        if (!empty($results['errors'])) {
            foreach ($results['errors'] as $error) {
                $this->error($error);
            }
        }

        return 0;
    }
}

