<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>School Kitchen — Главное меню</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-bg {
            background-image: url('{{ asset('images/home-bg.jpeg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen text-white">
    <div class="relative min-h-screen hero-bg">
        <div class="absolute inset-0 bg-black/50"></div>

        {{-- Центр экрана: крупный заголовок и большие кнопки --}}
        <main class="relative z-10 flex items-center justify-center h-screen px-6">
            <div class="text-center space-y-8">
                <h1 class="text-5xl md:text-6xl font-black drop-shadow-lg">SCHOOL KITCHEN</h1>
                <p class="text-xl md:text-2xl opacity-90">Быстрые и удобные заказы школьного питания</p>

                <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                    <a href="{{ route('order.page') }}"  class="px-8 py-4 text-2xl bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg">Сделать заказ</a>
                    <a href="{{ route('stats.page') }}"  class="px-8 py-4 text-2xl bg-green-600 hover:bg-green-700 rounded-xl shadow-lg">📊 Статистика</a>
                    <a href="{{ route('orders.page') }}" class="px-8 py-4 text-2xl bg-yellow-600 hover:bg-yellow-700 rounded-xl shadow-lg">📋 Таблица заказов</a>
                </div>
            </div>
        </main>
    </div>
</body>

<div class="absolute top-4 right-6 z-20">
    <img src="{{ asset('images/licei8.jpeg') }}" alt="Лицей №8" class="h-16 w-auto rounded-full shadow-lg">
</div>

</html>
