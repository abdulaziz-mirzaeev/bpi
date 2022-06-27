<?php


namespace app\models;


use yii\base\Model;

class ReportForm extends Model
{
    const REPORT_R7 = 0;
    const REPORT_R8 = 1;

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