<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика — School Kitchen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto py-8 px-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">📊 Шкала успеха по классам</h1>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← На главную</a>
        </div>

        <div class="bg-white rounded shadow p-6">
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">Класс</th>
                        <th class="border px-4 py-2">Количество заказов</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($successStats as $stat)
                        <tr>
                            <td class="border px-4 py-2">{{ $stat->class->name }}</td>
                            <td class="border px-4 py-2">{{ $stat->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
