<?php

namespace App\Console\Commands;

use App\Models\CryptoNews;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class FetchCryptoNews extends Command
{
    protected $signature = 'app:fetch-crypto-news';
    protected $description = 'Command description';

    public function handle()
    {
       try {
           $response = Http::get('https://cryptopanic.com/api/free/v1/posts/', [
               'auth_token' => '071446141389a94810b546b84fc9d752d11ef555',
           ]);

           if ($response->successful()) {
               $newsItems = $response->json()['results'];

               foreach ($newsItems as $news) {
                   CryptoNews::updateOrCreate(
                       ['external_id' => $news['id']],
                       [
                           'title' => $news['title'],
                           'date' => Carbon::parse($news['published_at'])->format('Y-m-d'),
                           'coin' => isset($news['currencies'][0]['code']) ? $news['currencies'][0]['code'] : null,
                       ]
                   );
               }
           } else {
               $this->error('Failed to fetch news');
           }

           $this->info('News fetched successfully.');
       } catch (Exception $e) {
           $this->error('An error occurred while fetching the news: ' . $e->getMessage());
           Log::error('Exception occurred while fetching crypto news', [
               'exception' => $e->getMessage(),
               'trace' => $e->getTraceAsString(),
           ]);
       }
    }
}
