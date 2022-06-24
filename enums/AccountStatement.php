<?php


namespace app\enums;


use MyCLabs\Enum\Enum;

class AccountStatement extends Enum
{
    const PROFIT_OR_LOSS = 'P&L Statement';
    const BALANCE_SHEET = 'Balance Sheet';
}