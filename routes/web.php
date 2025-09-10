<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AIAdviceController;





// ГЛАВНАЯ (картинка + три раздела)
Route::get('/', function () {
    return view('home');
})->name('home');

// СТАРАЯ страница "всё на одной" — оставляем для подстраховки
Route::get('/menu', [RequestController::class, 'index'])->name('menu.index');
// Если хочешь, можешь оставить и POST на /menu, он тоже будет работать:
Route::post('/menu', [RequestController::class, 'store'])->name('requests.store');

// НОВЫЕ отдельные разделы
Route::get('/order', [RequestController::class, 'orderPage'])->name('order.page');   // форма заказа
// Дополнительно разрешим и POST /order (на одну и ту же обработку):
Route::post('/order', [RequestController::class, 'store']); // без имени роута, чтобы не конфликтовал

Route::get('/stats', [RequestController::class, 'statsPage'])->name('stats.page');   // шкала успеха
Route::get('/orders', [RequestController::class, 'ordersPage'])->name('orders.page'); // таблица заказов

// Экспорт Excel
Route::get('/export-orders', [RequestController::class, 'export'])->name('orders.export');

// Управление заказами
Route::get('/orders/{order}/edit', [RequestController::class, 'edit'])->name('orders.edit');
Route::put('/orders/{order}', [RequestController::class, 'update'])->name('orders.update');
Route::delete('/orders/{order}', [RequestController::class, 'destroy'])->name('orders.destroy');



Route::get('/api/daily-advice', [AIAdviceController::class, 'daily'])->name('ai.daily');
