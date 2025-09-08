<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Таблица заказов — School Kitchen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto py-8 px-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">📋 Таблица заказов</h1>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← На главную</a>
        </div>

        <a href="{{ route('orders.export') }}"
           target="_blank"
           class="inline-block mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
           📥 Скачать заказы в Excel
        </a>

        <div class="bg-white rounded shadow p-6">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ФИО ученика</th>
                        <th class="border px-4 py-2">Класс</th>
                        <th class="border px-4 py-2">Блюда</th>
                        <th class="border px-4 py-2">Сумма</th>
                        <th class="border px-4 py-2">Дата и время</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="border px-4 py-2">{{ $order->student_name }}</td>
                            <td class="border px-4 py-2">{{ $order->class->name }}</td>
                            <td class="border px-4 py-2">
                                <ul>
                                    @foreach($order->menuItems as $item)
                                        <li>{{ $item->name }} — {{ $item->price }} ₸</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border px-4 py-2 font-semibold">{{ $order->total_price }} ₸</td>
                            <td class="border px-4 py-2">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
