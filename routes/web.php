<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
//use App\Exports\OrdersExport;
//use Maatwebsite\Excel\Facades\Excel;


Route::get('/export-orders', [RequestController::class, 'export'])->name('orders.export');

Route::redirect('/', '/menu');

Route::get('/menu', [RequestController::class, 'index'])->name('menu.index');
Route::post('/menu', [RequestController::class, 'store'])->name('requests.store');

//for excel exporting file

//Route::get('/export/orders', function () {
    //return Excel::download(new OrdersExport, 'orders.xlsx');
//})->name('orders.export');
