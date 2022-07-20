<?php


namespace app\models;


use app\enums\AccountStatement;

class ReportPL_VR2 extends ReportYOY
{
    public $recordPairClass = RecordPairPL_VR2::class;
}