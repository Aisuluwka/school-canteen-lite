<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование заказа</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
<div class="max-w-4xl mx-auto py-10 px-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Редактирование заказа</h1>

    <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="student_name" class="block font-medium">ФИО ученика</label>
            <input type="text" name="student_name" id="student_name"
                   value="{{ old('student_name', $order->student_name) }}"
                   class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label for="class_id" class="block font-medium">Класс</label>
            <select name="class_id" id="class_id" class="w-full border rounded p-2" required>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" 
                        {{ $class->id == $order->class_id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <h3 class="text-lg font-semibold mb-2">Меню</h3>
        @forelse($menuToday as $type => $items)
            <h4 class="font-semibold mt-4 mb-2">{{ ucfirst($type) }}</h4>
            <div class="space-y-1">
                @foreach($items as $item)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="menu_items[]" value="{{ $item->id }}"
                               {{ $order->menuItems->contains($item->id) ? 'checked' : '' }}>
                        <span>{{ $item->name }} — {{ $item->price }} ₸</span>
                    </label>
                @endforeach
            </div>
        @empty
            <p class="text-gray-600">Меню пусто.</p>
        @endforelse

        <div class="mt-6 flex gap-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                💾 Сохранить
            </button>
            <a href="{{ route('orders.page') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                Отмена
            </a>
        </div>
    </form>
</div>
</body>
</html>
