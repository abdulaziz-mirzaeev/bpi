<?php


namespace app\models;


use yii\base\Model;

class ReportForm extends Model
{
    const REPORT_R7 = 0;
    const REPORT_R8 = 1;

    public $monthPrevious;
    public $monthActual;
    public $monthPlan;

    public $reportId;

    public function rules()
    {
        return [
            [['monthPrevious', 'monthActual', 'monthPlan', 'reportId'], 'required'],
            [['monthPrevious', 'monthActual', 'monthPlan', 'reportId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'monthPrevious' => 'Previous',
            'monthActual' => 'Actual',
            'monthPlan' => 'Plan',
            'reportId' => 'Report'
        ];
    }
}