<?php

namespace app\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class Tools
{
    public static function pr($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    public static function getParam($key, $default = null)
    {
        return ArrayHelper::getValue(Yii::$app->params, $key, $default);
    }

}