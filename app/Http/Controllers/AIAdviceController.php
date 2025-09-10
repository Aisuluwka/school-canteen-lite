<?php

namespace App\Http\Controllers;

use App\Services\DailyAdviceService;

class AIAdviceController extends Controller
{
    public function daily(DailyAdviceService $svc)
    {
        return response()->json($svc->getTodayAdvice());
    }
}
