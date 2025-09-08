<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with('class', 'menuItems')->get()->map(function ($order) {
            return [
                'ФИО ученика' => $order->student_name,
                'Класс' => $order->class->name,
                'Блюда' => $order->menuItems->pluck('name')->join(', '),
                'Сумма' => $order->total_price . ' ₸',
                'Дата и время' => $order->created_at->format('d.m.Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ФИО ученика',
            'Класс',
            'Блюда',
            'Сумма',
            'Дата и время',
        ];
    }
}
