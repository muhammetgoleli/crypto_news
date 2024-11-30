<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CryptoNewsController extends Controller
{
    public function index()
    {
        return view('news.index');
    }

    public function getNews(Request $request)
    {
        $news = json_decode(Cache::get('crypto_news'), true) ?? [];
        //dd($news);
        $filteredNews = collect($news)->filter(function ($item) use ($request) {
            $startDate = $request->start_date ? strtotime($request->start_date) : null;
            $endDate = $request->end_date ? strtotime($request->end_date) : null;
            $coin = $request->coin;

            $publishedAt = strtotime($item['date']);
            return (!$coin || $item['coin'] === $coin) &&
                (!$startDate || $publishedAt >= $startDate) &&
                (!$endDate || $publishedAt <= $endDate);
        });

        return response()->json(['news' => $filteredNews->values()]);
    }
    public function coins()
    {
        $news = json_decode(Cache::get('crypto_news'), true) ?? [];
        $coins = collect($news)->pluck('coin')->unique()->values();
        return response()->json(['coins' => $coins]);
    }
}
