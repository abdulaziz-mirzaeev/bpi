<?php


namespace app\enums;


use MyCLabs\Enum\Enum;

class ScoreCodes extends Enum
{
    const NET_SALES_SCORE = '1.0';
    const GP_SCORE = '2.0';
    const GP_ROS_PERCENT_CHANGE_SCORE = '2.4';
    const COGS_ACTUAL_TO_PLAN_PERCENT = '2.5';
    const GP_ACTUAL_TO_PLAN_PERCENT = '2.6';
    const SG_AND_A_ACTUAL_TO_SALES_PERCENT = '3.5';
    const OI_ACTUAL_TO_PLAN_PERCENT = '3.6';
    const NONOP_ACTUAL_TO_PLAN_PERCENT = '4.5';
    const NI_ACTUAL_TO_PLAN_PERCENT = '4.6';
    const NI_SCORE = '4.0';
    const NI_ROS_PERCENT_CHANGE_SCORE = '4.4';
}