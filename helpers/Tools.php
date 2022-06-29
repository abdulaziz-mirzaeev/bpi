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

    public static function printRowMessage(string $message)
    {
        echo "<tbody>";
        echo "<tr><td colspan='11' class='text-break fw-light'>{$message}</td></tr>";
        echo "</tbody>";
    }

    public static function printEmptyRow()
    {
        echo "<tbody>";
        echo "<tr><td colspan='11'><p></p></td></tr>";
        echo "</tbody>";
    }
}