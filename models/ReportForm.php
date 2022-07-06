<?php


namespace app\models;


use yii\base\Model;

class ReportForm extends Model
{
    const REPORT_R7 = 0;
    const REPORT_R8 = 1;
    const REPORT_PT1 = 2;
    const REPORT_PT2 = 3;

    public static $reportNames = [
        self::REPORT_R7 => 'Report R7',
        self::REPORT_R8 => 'Report R8',
        self::REPORT_PT1 => 'Actual To Plan P&L (PT#1)',
        self::REPORT_PT2 => 'Year Over Year P&L (PT#2)'
    ];

    public $month;

    public $reportId;

    public function rules()
    {
        return [
            [['month', 'reportId'], 'required'],
            [['month', 'reportId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'month' => 'Month',
            'reportId' => 'Report'
        ];
    }
}