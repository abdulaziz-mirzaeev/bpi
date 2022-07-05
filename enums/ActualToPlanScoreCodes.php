<?php


namespace app\enums;


use MyCLabs\Enum\Enum;

class ActualToPlanScoreCodes extends Enum
{
    const NET_SALES_SCORE = '1.0';
    const COGS_ACTUAL_TO_PLAN_PERCENT = '2.5';
    const SG_AND_A_ACTUAL_TO_SALES_PERCENT = '3.5';
    const GP_ACTUAL_TO_PLAN_PERCENT = '2.6';
    const OI_ACTUAL_TO_PLAN_PERCENT = '3.6';
    const NONOP_ACTUAL_TO_PLAN_PERCENT = '4.5';
    const NI_ACTUAL_TO_PLAN_PERCENT = '4.6';
}