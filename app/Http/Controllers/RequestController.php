<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;



class RequestController extends Controller
{
    // Показываем форму + меню + шкалу успеха + таблицу заказов
    public function index()
    {
        $today = now()->dayOfWeekIso; // 1=Пн, ..., 7=Вс
    
        $menuItems = MenuItem::where('day', $today)->get();
        $menuToday = $menuItems->groupBy('type');
    
        $classes = ClassModel::orderBy('name')->get();
    
        $successStats = Order::select('class_id', DB::raw('COUNT(*) as total'))
            ->groupBy('class_id')
            ->orderByDesc('total')
            ->with('class')
            ->get();
    
        $orders = Order::with(['class', 'menuItems'])->latest()->get();
    
        // 👇 Добавим поле total_price
        foreach ($orders as $order) {
            $order->total_price = $order->menuItems->sum('price');
        }
    
        return view('student_request.index', compact('menuToday', 'classes', 'successStats', 'orders'));
    }
    

    // Обрабатываем заявку
    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'class_id'     => 'required|exists:classes,id',
            'menu_items'   => 'required|array|min:1',
            'menu_items.*' => 'exists:menu_items,id',
        ]);

        $order = Order::create([
            'student_name' => $request->student_name,
            'class_id'     => $request->class_id,
        ]);

        $order->menuItems()->attach($request->menu_items);

        return back()->with('success', 'Заявка отправлена!');

    }

    public function export()
{
    return Excel::download(new OrdersExport, 'orders.xlsx');
}


// Страница: только форма "Сделать заказ"
public function orderPage()
{
    $today = now()->dayOfWeekIso; // 1=Пн..7=Вс
    $menuItems = MenuItem::where('day', $today)->get();
    $menuToday = $menuItems->groupBy('type');

    $classes = ClassModel::orderBy('name')->get();

    return view('student_request.order', compact('menuToday', 'classes'));
}

// Страница: только "Шкала успеха"
public function statsPage()
{
    $successStats = Order::select('class_id', DB::raw('COUNT(*) as total'))
        ->groupBy('class_id')
        ->orderByDesc('total')
        ->with('class')
        ->get();

    return view('student_request.stats', compact('successStats'));
}

// Страница: только "Таблица заказов"
public function ordersPage()
{
    $orders = Order::with(['class', 'menuItems'])->latest()->get();

    foreach ($orders as $order) {
        $order->total_price = $order->menuItems->sum('price');
    }

    return view('student_request.orders', compact('orders'));
}

// Форма редактирования
public function edit(Order $order)
{
    $classes = ClassModel::orderBy('name')->get();
    $menuToday = MenuItem::where('day', now()->dayOfWeekIso)->get()->groupBy('type');

    return view('student_request.edit_order', compact('order', 'classes', 'menuToday'));
}

// Обновление заказа
public function update(Request $request, Order $order)
{
    $request->validate([
        'student_name' => 'required|string|max:255',
        'class_id'     => 'required|exists:classes,id',
        'menu_items'   => 'required|array|min:1',
        'menu_items.*' => 'exists:menu_items,id',
    ]);

    $order->update([
        'student_name' => $request->student_name,
        'class_id'     => $request->class_id,
    ]);

    // обновляем выбранные блюда
    $order->menuItems()->sync($request->menu_items);

    return redirect()->route('orders.page')->with('success', 'Заказ обновлён!');
}

// Удаление заказа
public function destroy(Order $order)
{
    $order->delete();
    return redirect()->route('orders.page')->with('success', 'Заказ удалён!');
}


}
