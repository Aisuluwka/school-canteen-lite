<?php

namespace App\Services;

use App\Models\MenuItem;
use Carbon\Carbon;
use Illuminate\Support\Str;
use OpenAI;

class DailyAdviceService
{
    public function getTodayAdvice(): array
    {
        $now = Carbon::now('Asia/Almaty');
        $weekday = $now->isoWeekday();

        $menuToday = MenuItem::where('day', $weekday)->get();
        $cacheKey = "ai_advice_{$now->toDateString()}";

        // 👉 сохраняем совет в кэше на 24 часа
        return cache()->remember($cacheKey, 86400, function () use ($menuToday, $weekday, $now) {
            // Сначала пробуем ChatGPT
            $aiAdvice = $this->getAdviceFromAI($menuToday);

            if ($aiAdvice) {
                return [
                    'date' => $now->toDateString(),
                    'weekday' => $this->weekdayRu($weekday),
                    'picks' => $menuToday->pluck('name')->toArray(),
                    'text' => $aiAdvice,
                ];
            }

            // Фоллбек: твой алгоритм
            [$pick1, $pick2] = $this->pickPair($menuToday);
            $text = $this->makeAdviceText($weekday, $pick1, $pick2);

            return [
                'date' => $now->toDateString(),
                'weekday' => $this->weekdayRu($weekday),
                'picks' => array_values(array_filter([$pick1, $pick2])),
                'text' => $text,
            ];
        });
    }

    private function getAdviceFromAI($menuToday): ?string
    {
        try {
            $menuList = $menuToday->pluck('name')->implode(', ');

            $prompt = "Сегодняшнее меню: {$menuList}. 
            Дай короткий совет ученикам (1–2 предложения), что лучше выбрать и почему. 
            Пиши дружелюбно и просто.";

            $client = OpenAI::client(env('OPENAI_API_KEY'));

            $result = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Ты умный помощник школьной столовой.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 120,
            ]);

            return trim($result->choices[0]->message->content ?? '');
        } catch (\Exception $e) {
            \Log::error('AI Advice error: '.$e->getMessage());
            return null;
        }
    }

    private function weekdayRu(int $iso): string
    {
        return [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            7 => 'Воскресенье',
        ][$iso] ?? 'День';
    }

    private function pickPair($items): array
    {
        $soup = $items->first(fn($i) => Str::contains(mb_strtolower($i->name), ['суп','борщ','щи','рассольник','уха']));
        $meat = $items->first(fn($i) => Str::contains(mb_strtolower($i->name), ['котлет','куриц','говядин','тефтел','жаркое']));
        $side = $items->first(fn($i) => Str::contains(mb_strtolower($i->name), ['пюре','рис','греч','плов','макарон']));

        if ($soup && $side) return [$soup->name, $side->name];
        if ($meat && $side) return [$meat->name, $side->name];

        return [$items->get(0)?->name, $items->get(1)?->name];
    }

    private function makeAdviceText(int $weekday, ?string $pick1, ?string $pick2): string
    {
        $lines = [
            1 => 'Начало недели лучше встретить сбалансированным обедом.',
            2 => 'Во вторник важно поддерживать энергию до конца дня.',
            3 => 'Среда — середина недели: выбирайте блюда, которые не перегрузят.',
            4 => 'Четверг требует сил для финального рывка.',
            5 => 'В пятницу можно позволить себе вкусное, но не забывайте про баланс.',
            6 => 'Суббота — выбирайте питательно, но без тяжести.',
            7 => 'Воскресенье — мягкий комфортный баланс блюд.',
        ];

        $weekdayRu = $this->weekdayRu($weekday);
        $pair = $pick1 ? "Хороший выбор — {$pick1}".($pick2 ? " и {$pick2}" : '') : '';
        $hint = $lines[$weekday] ?? '';

        return "Сегодня {$weekdayRu}. {$pair}. {$hint}";
    }
}
