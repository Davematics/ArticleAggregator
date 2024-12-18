<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleFetcher\GuardianFetcherService;
use App\Services\ArticleFetcher\AiNewsApiFetcherService;
use App\Services\ArticleFetcher\NewsApiFetcherService;

class FetchArticles extends Command
{
    protected $signature = 'articles:fetch';

    protected $description = 'Fetch articles from different sources';

    public function handle()
    {
        $fetchers = [new GuardianFetcherService(), new NewsApiFetcherService(), new AiNewsApiFetcherService()];

        foreach ($fetchers as $fetcher) {
            $fetcher->fetchArticles();
        }
        foreach ($fetchers as $fetcher) {
            dispatch(function () use ($fetcher) {
                $fetcher->fetchArticles();
            })->onQueue('article-fetching');
        }

        $this->info('Article fetching jobs have been dispatched.');
    }
}
