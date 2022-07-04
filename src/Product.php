<?php

namespace App;

class Product
{
    public static function formatCapacity($capacity)
    {
        $size = (int)$capacity;

        //check if capacity is in GB
        if (strpos($capacity, 'GB')) {
            $size *= 1000;  //convert to mb
        }
        return $size;
    }



    public static function formatAvailability($availability)
    {
        return (bool)strpos($availability, 'In Stock');
    }

    

    public static function formatShippingDate($shippingDetail)
    {
        // checking different date format
        if ($shippingDetail && preg_match("/(\d{1,2}) (\w+) (\d{4})/", $shippingDetail, $result) || preg_match("/\d{4}-\d{2}-\d{2}/", $shippingDetail, $result) || preg_match("/\w+day\s\d{1,2}(st|th|rd|nd)\s\w+ \d{4}/", $shippingDetail, $result)   || preg_match("/tomorrow/", $shippingDetail, $result)) {
            return date('Y-m-d', strtotime($result[0]));
        }
        return '';
    }
}
