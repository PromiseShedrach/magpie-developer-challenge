<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

require '../vendor/autoload.php';


class Scrape
{
    private array $products = [];





    public function run()
    {

        return ScrapeHelper::paginationLinks('https://www.magpiehq.com/developer-challenge/smartphones');
        file_put_contents('output.json', json_encode($this->products));
    }


    public function crawSiteContent()
    {
        $paginations = ScrapeHelper::paginationLinks('https://www.magpiehq.com/developer-challenge/smartphones');

        foreach ($paginations as $pagination) {
            $link = 'https://www.magpiehq.com/developer-challenge/smartphones' . '/?page=' . $pagination;
            $document = ScrapeHelper::fetchDocument($link);
            $productContainers = $document->filter('.product');
            return $productContainers->each(function (Crawler $node, $i) {
                return $i;
            });
        }
    }
}

$scrape = new Scrape();
print_r($scrape->crawSiteContent());
