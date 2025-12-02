# Исправление проблемы с паролями

## Проблема
Ошибка: `This password does not use the Bcrypt algorithm`

## Причина
Пароли в БД имеют неправильный формат (обрезаны или используют другой алгоритм хеширования).

## Решение

### Вариант 1: Выполнить SQL скрипт (РЕКОМЕНДУЕТСЯ)

1. Откройте phpMyAdmin или MySQL клиент
2. Выберите базу данных `arviay_shop`
3. Выполните SQL из файла `reset_passwords.sql`

Или выполните через командную строку:
```bash
mysql -u ваш_пользователь -p arviay_shop < reset_passwords.sql
```

### Вариант 2: Обновить пароли через Tinker

Выполните в терминале:

```bash
php artisan tinker
```

Затем в консоли tinker:

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Обновить пароль администратора
$admin = User::where('email', 'admin@arviay.ru')->first();
$admin->password = Hash::make('admin123');
$admin->save();

// Обновить пароль клиента 1
$client1 = User::where('email', 'client1@test.ru')->first();
$client1->password = Hash::make('client123');
$client1->save();

// Обновить пароль клиента 2
$client2 = User::where('email', 'client2@test.ru')->first();
$client2->password = Hash::make('client123');
$client2->save();

exit
```

### Учетные данные после обновления

- **Администратор**: `admin@arviay.ru` / `admin123`
- **Клиент 1**: `client1@test.ru` / `client123`
- **Клиент 2**: `client2@test.ru` / `client123`

## После исправления

После обновления паролей попробуйте войти снова. Проблема должна быть решена.

