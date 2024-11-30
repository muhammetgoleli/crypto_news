<?php

namespace App\Console\Commands;

use App\Models\CryptoNews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheCryptoNews extends Command
{

    protected $signature = 'app:cache-crypto-news';
    protected $description = 'Command description';

    public function handle()
    {
        $news = CryptoNews::all();
        Cache::put('crypto_news', $news, 3600);

        $this->info('Crypto news cached successfully.');
    }
}
