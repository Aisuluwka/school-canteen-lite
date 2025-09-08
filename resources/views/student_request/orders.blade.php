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
           class="inline-block mb-4 px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
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
                        <th class="border px-4 py-2">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $order->student_name }}</td>
                            <td class="border px-4 py-2">{{ $order->class->name }}</td>
                            <td class="border px-4 py-2">
                                <ul class="list-disc list-inside text-sm text-gray-700">
                                    @foreach($order->menuItems as $item)
                                        <li>{{ $item->name }} — {{ $item->price }} ₸</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="border px-4 py-2 font-semibold text-gray-900">{{ $order->total_price }} ₸</td>
                            <td class="border px-4 py-2 text-sm text-gray-600">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="border px-4 py-2">
                                <div class="flex gap-2">
                                    {{-- Редактировать --}}
                                    <a href="{{ route('orders.edit', $order) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-400 text-gray-900 text-sm font-medium rounded-lg shadow hover:bg-yellow-500 transition">
                                        ✏️ <span>Редактировать</span>
                                    </a>
                                    
                                    {{-- Удалить --}}
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Удалить этот заказ?')" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white text-sm font-medium rounded-lg shadow hover:bg-red-600 transition">
                                            🗑️ <span>Удалить</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

