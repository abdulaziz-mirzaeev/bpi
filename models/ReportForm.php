<?php


namespace app\models;


use yii\base\Model;

class ReportForm extends Model
{
    const REPORT_R7 = 0;
    const REPORT_R8 = 1;
    const REPORT_PT1 = 2;
    const REPORT_PT2 = 3;
    const REPORT_PL_VR1 = 4;
    const REPORT_PL_VR2 = 5;
    const REPORT_PL_VR3 = 6;

    public static $reportNames = [
        self::REPORT_R7 => 'Report R7',
        self::REPORT_R8 => 'Report R8',
        self::REPORT_PT1 => 'Actual To Plan P&L (PT#1)',
        self::REPORT_PT2 => 'Year Over Year P&L (PT#2)',
        self::REPORT_PL_VR1 => 'P&L VR#1',
        self::REPORT_PL_VR2 => 'P&L VR#2',
        self::REPORT_PL_VR3 => 'P&L VR#3',
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