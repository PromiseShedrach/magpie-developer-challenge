<?php

namespace App;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper
{

    public static function fetchDocument(string $url): Crawler
    {
        $client = new Client();
        $response = $client->get($url);
        return  $crawler = new Crawler($response->getBody()->getContents(), $url);
    }


    //get all links by extracting pagination page numbers
    public static function fetchPagination($url)
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
