<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

require '../vendor/autoload.php';


class Scrape
{
    private array $products = [];
    private string $base_url = 'https://www.magpiehq.com/developer-challenge/smartphones';


    public function run(): void
    {
        $this->crawlSiteContent();
        file_put_contents('output.json', json_encode($this->products));
    }


    public function crawlSiteContent(): void
    {
        $paginations = ScrapeHelper::fetchPagination($this->base_url);

        foreach ($paginations as $pagination) {
            $link = $this->base_url . '/?page=' . $pagination;
            $document = ScrapeHelper::fetchDocument($link);
            $products = $document->filter('.product');
            $products->each(
                function (Crawler $node, $i) {
                    $this->formatProduct($node);
                }
            );
        }
    }


    public function formatProduct(Crawler $node): void
    {
        $allColors = $node->filter('.border.border-black.rounded-full.block');
        $colours = $allColors->each(
            function (Crawler $node, $i) {
                return $node->attr('data-colour');
            }
        );

        //get variables
        $title = $node->filter('.product h3')->text();
        $price = ltrim($node->filter('.block.text-center.text-lg')->text(), '£');
        $capacity = $node->filter('.product-capacity')->text();
        $availability = $node->filter('.product .bg-white div:nth-child(5)')->text();
        $shippingDetail = $node->filter('.my-4.text-sm.block.text-center')->eq(1)->text('');
        $image = $node->filter('img')->image()->getUri();

        foreach ($colours as $colour) {
            array_push(
                $this->products,
                [
                    'title' => $title,
                    'price' => (float)$price,
                    'imageUrl' => $image,
                    'capacityMB' => Product::formatCapacity($capacity),
                    'color' => $colour,
                    'availabilityText' => Product::formatAvailability($availability) ? 'In Stock' : 'Out of Stock',
                    'isAvailable' =>  Product::formatAvailability($availability),
                    'shippingText' =>  $shippingDetail,
                    'shippingDate' =>  Product::formatShippingDate($shippingDetail)
                ]
            );
        }
    }

}



$scrape = new Scrape();
$scrape->run();
