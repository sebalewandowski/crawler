<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    public function __construct()
    {
    }

    public function crawlWebsite()
    {
        $url = 'https://www.spotifyjobs.com/jobs';
        $crawler = new Crawler();
        $crawler->add(file_get_contents($url));

        $s = $crawler->filter('.select_select__3bDim')->add('.select_open__3XKd5');
        dd($s);
        // Collect info
//        $productsInfo = $crawler
//            ->filter('.container block-container mb-m mb-mobile-m > row');

        // Collect info
//        $productsInfo = $crawler
//            ->filter('.main')->html()
//        ;


//        dd($productsInfo);
    }
}