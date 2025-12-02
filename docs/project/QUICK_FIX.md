# Быстрое исправление ошибки с таблицей sessions

## Проблема
Ошибка: `Table 'arviay_shop.sessions' doesn't exist`

## Решение

### Вариант 1: Выполнить SQL скрипт (РЕКОМЕНДУЕТСЯ)

1. Откройте phpMyAdmin или MySQL клиент
2. Выберите базу данных `arviay_shop`
3. Выполните SQL из файла `fix_missing_tables.sql`

Или выполните через командную строку:
```bash
mysql -u ваш_пользователь -p arviay_shop < fix_missing_tables.sql
```

### Вариант 2: Создать только таблицу sessions

Выполните этот SQL:

```sql
USE `arviay_shop`;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Вариант 3: Изменить драйвер сессий на file

Если не хотите использовать таблицу sessions, можно изменить драйвер сессий в `.env`:

```env
SESSION_DRIVER=file
```

Затем очистите кэш:
```bash
php artisan config:clear
```

## После исправления

После создания таблицы `sessions` сайт должен работать корректно. Обновите страницу в браузере.

