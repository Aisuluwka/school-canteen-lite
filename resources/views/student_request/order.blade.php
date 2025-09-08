<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сделать заказ — School Kitchen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto py-8 px-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">🍽 Сделать заказ</h1>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">← На главную</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow p-6">
            <form method="POST" action="{{ route('requests.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="student_name" class="block font-medium">ФИО ученика</label>
                    <input type="text" name="student_name" id="student_name" class="w-full border rounded p-2" required>
                </div>

                <div class="mb-4">
                    <label for="class_id" class="block font-medium">Класс</label>
                    <select name="class_id" id="class_id" class="w-full border rounded p-2" required>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <h3 class="text-lg font-semibold mb-2">Меню на сегодня</h3>

                @forelse($menuToday as $type => $items)
                    <h4 class="font-semibold mt-4 mb-2">{{ ucfirst($type) }}</h4>
                    <div class="space-y-1">
                        @foreach($items as $item)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="menu_items[]" value="{{ $item->id }}" data-price="{{ $item->price }}">
                                <span>{{ $item->name }} — {{ $item->weight }} г — {{ $item->price }} ₸</span>
                            </label>
                        @endforeach
                    </div>
                @empty
                    <p class="text-gray-600">Сегодня меню не заполнено.</p>
                @endforelse

                <div class="mt-4 font-semibold">Итого: <span id="total-price">0</span> ₸</div>

                <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Отправить заявку
                </button>
            </form>
        </div>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-price]');
        const totalPriceEl = document.getElementById('total-price');
        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => { if (cb.checked) total += parseInt(cb.dataset.price); });
            totalPriceEl.textContent = total;
        }
        checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
        updateTotal();
    </script>
</body>
</html>
