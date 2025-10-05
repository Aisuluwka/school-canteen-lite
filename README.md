# 🍽️ School Canteen Lite

Простой веб-сервис для школьной столовой, созданный на **Laravel 10**.  
Ученики могут делать заказы на питание, а администратор — видеть статистику и заказы по классам.

---

## 🚀 Демо

Исходный код:  
👉 [GitHub Repository](https://github.com/aisuluwka/school-canteen-lite)

---

## ✨ Возможности

-   Просмотр меню на каждый день недели
-   Автоматический расчёт стоимости выбранных блюд
-   Отправка заявок на питание с указанием класса и ФИО
-   Таблица заказов для администратора
-   Шкала успеха по классам (какой класс сделал больше заказов)
-   Экспорт заказов в Excel
-   🤖 **ИИ-помощник**: совет дня от ChatGPT API (рекомендации по выбору блюд)

---

## 🛠️ Технологии

-   **Backend:** Laravel 10 (PHP 8.3, SQLite)
-   **Frontend:** Blade, TailwindCSS
-   **AI:** OpenAI API (ChatGPT integration)
-   **DB:** SQLite (по умолчанию, можно заменить на PostgreSQL/MySQL)

---

## ⚡ Установка и запуск локально

Скопируй команды по порядку 👇

```bash
# Клонировать проект
git clone https://github.com/aisuluwka/school-canteen-lite.git
cd school-canteen-lite

# Установить зависимости
composer install
npm install && npm run build

# Создать файл .env и сгенерировать ключ
cp .env.example .env
php artisan key:generate

# Настроить SQLite в .env (пример)
echo "DB_CONNECTION=sqlite" >> .env
echo "DB_DATABASE=$(pwd)/database/database.sqlite" >> .env

# Создать файл базы
touch database/database.sqlite

# Прогнать миграции и сиды
php artisan migrate --seed

# Запустить сервер
php artisan serve


### 👩‍🏫 Руководитель проекта:
Абикенова Айсулу, учитель биологии КГУ «Школа-лицей №8 для одарённых детей», г. Павлодар

### 👩‍🎓 Авторы проекта:
Жақсылық Айқын, Бекпаева Алина — ученики 9 класса
Проект выполнен в рамках апробации и защиты школьных проектов.


```
