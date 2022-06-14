<?php

namespace app\helpers;

class Tools
{
    public static function pr($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

}