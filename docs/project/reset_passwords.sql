-- SQL скрипт для обновления паролей пользователей
-- Выполните этот SQL в вашей БД arviay_shop
-- Пароли будут установлены: admin123 для админа, client123 для клиентов

USE `arviay_shop`;

-- Обновляем пароль администратора (admin123)
UPDATE `users` 
SET `password` = '$2y$12$CDmXCQvgQTmMnAjrciXGneOFx8IqbJZysWNkrRPPNXVuwzpc7G.g2' 
WHERE `email` = 'admin@arviay.ru';

-- Обновляем пароль клиента 1 (client123)
UPDATE `users` 
SET `password` = '$2y$12$vyvuBkLUww54xu7NB18tjexcyOK0NCYJAmTg5OXmvqwK1hURisj8O' 
WHERE `email` = 'client1@test.ru';

-- Обновляем пароль клиента 2 (client123)
UPDATE `users` 
SET `password` = '$2y$12$vyvuBkLUww54xu7NB18tjexcyOK0NCYJAmTg5OXmvqwK1hURisj8O' 
WHERE `email` = 'client2@test.ru';

-- Учетные данные для входа:
-- Администратор: admin@arviay.ru / admin123
-- Клиент 1: client1@test.ru / client123
-- Клиент 2: client2@test.ru / client123

