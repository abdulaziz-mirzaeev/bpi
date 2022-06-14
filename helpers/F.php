<?php


namespace app\helpers;


use Yii;

class F
{
    public static function percent($value, $decimals = null)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        return Yii::$app->formatter->asPercent($value, $decimals);
    }

    public static function percentSign($value)
    {
        return $value . '%';
    }

    public static function decimal($value)
    {
        return Yii::$app->formatter->asDecimal($value, 0);
    }

    public static function equiv100($value)
    {
        return round($value * 100);
    }
}