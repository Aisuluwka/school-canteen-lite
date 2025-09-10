<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заявка на питание</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto py-10 px-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Заявка на питание</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- СОВЕТ ДНЯ --}}
        <section id="daily-advice" class="mb-8 p-4 rounded-xl border bg-gray-50 shadow">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Совет дня от умной столовой</h2>
            <button id="refreshAdvice" class="text-sm px-3 py-1 rounded border">Обновить</button>
          </div>
          <p id="adviceText" class="mt-2 text-gray-800">Загружаем совет…</p>
          <p id="adviceMeta" class="mt-1 text-xs text-gray-500"></p>
        </section>

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

        {{-- ШКАЛА УСПЕХА --}}
        <hr class="my-10">
        <h2 class="text-xl font-bold mb-4">🏆 Шкала успеха по классам</h2>
        <table class="w-full table-auto border-collapse border border-gray-300 mb-10">
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

        <a href="{{ route('orders.export') }}" 
           target="_blank"
           class="inline-block mb-4 px-4 py-2 bg-green-600 text-white rounded 
                  hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
           📥 Скачать заказы в Excel
        </a>

        {{-- ТАБЛИЦА ЗАКАЗОВ --}}
        <h2 class="text-xl font-bold mb-4">📋 Таблица заказов</h2>
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
                        <td class="border px-4 py-2 font-semibold">
                            {{ $order->total_price }} ₸
                        </td>
                        <td class="border px-4 py-2">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        // Скрипт для подсчёта итоговой суммы при выборе блюд
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-price]');
        const totalPriceEl = document.getElementById('total-price');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.dataset.price);
                }
            });
            totalPriceEl.textContent = total;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
        updateTotal();

        // Скрипт для «Совета дня»
        async function fetchAdvice() {
          const textEl = document.getElementById('adviceText');
          const metaEl = document.getElementById('adviceMeta');

          textEl.textContent = 'Загружаем совет…';
          metaEl.textContent = '';

          try {
            const res = await fetch('/api/daily-advice');

            //const res = await fetch('{{ route('ai.daily') }}');
            const data = await res.json();
            textEl.textContent = data.text;
            const picks = (data.picks && data.picks.length) ? ` (например: ${data.picks.join(' + ')})` : '';
            metaEl.textContent = `${data.weekday}${picks}`;
          } catch (e) {
            textEl.textContent = 'Сегодня отличное меню — выбирайте сбалансированно!';
          }
        }
        document.getElementById('refreshAdvice').addEventListener('click', fetchAdvice);
        fetchAdvice();
    </script>
</body>
</html>
