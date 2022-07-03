<?php

namespace App;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper
{

    public static function fetchDocument(string $url)
    {
        $client = new Client();

        $response = $client->get($url);

        return  $crawler = new Crawler($response->getBody()->getContents(), $url);

        $titles = $crawler->filter('.product-name')->extract(['_text']);
        $prices = $crawler->filter('.product .bg-white div:nth-child(4)')->extract(['_text']);
        $capacities = $crawler->filter('.product-capacity')->extract(['_text']);
        $availability = $crawler->filter('.product .bg-white div:nth-child(5)')->extract(['_text']);
        $shippingTexts = $crawler->filter('.product .bg-white div:nth-child(6)')->extract(['_text']);

        //pagination links
        $links = $crawler->filter('#pages a')->extract(['_text']);

        $colourNodes = $crawler->filter('.border.border-black.rounded-full.block');
        return  $colours = $colourNodes->each(function (Crawler $node, $i) {
            return $node->attr('data-colour');
        });
    }


    //get all links
    public static function paginationLinks($url)
    {
        $crawLink = self::fetchDocument($url);
        $pages = $crawLink->filter('#pages a');
        return $pages->each(function (Crawler $item, $i) {
            return $item->text();
        });
    }
}

 



// {
//     "title": "iPhone 11 Pro 64GB",   ***
//     "price": 123.45, ***
//     "imageUrl": "https://example.com/image.png",
//     "capacityMB": 64000, ***
//     "colour": "red",
//     "availabilityText": "In Stock", ***
//     "isAvailable": true, 
//     "shippingText": "Delivered from 25th March", ***
//     "shippingDate": "2021-03-25"
// }