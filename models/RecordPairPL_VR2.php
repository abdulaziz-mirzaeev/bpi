<?php


namespace app\models;

use app\enums\AccountId;
use Yii;

/**
 * @property ReportPL_VR2 $model
 */
class RecordPairPL_VR2 extends RecordPairYOY
{
    public function additionalSpendOfSalesInPercent($formatting = true, $decimals = 0)
    {
        $value = $this->actualChangeInDollars(false) /
            $this->model->getRecordByAccount(AccountId::NET_SALES)->actualChangeInDollars(false);

        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function lowOfSalesRangeInPercent($formatting = true, $decimals = 0)
    {
        $value = $this->percentagePreviousToSales(false) * $this->model->lowOfSalesRange;
        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function highOfSalesRangeInPercent($formatting = true, $decimals = 0)
    {
        $value = $this->percentagePreviousToSales(false) * $this->model->highOfSalesRange;
        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function lowOrHighOfSales()
    {
        if ($this->account->id == AccountId::NET_SALES) {
            $actualChangeInPercent = $this->actualChangeInPercent(false);
            $lowOfChange = $this->model->lowOfChange;
            $highOfChange = $this->model->highOfChange;

            if ($actualChangeInPercent < $lowOfChange) {
                return 'Lower';
            } elseif ($actualChangeInPercent > $highOfChange) {
                return 'Higher';
            } elseif ($actualChangeInPercent >= $lowOfChange || $actualChangeInPercent <= $highOfChange) {
                return '=';
            }
        } else {
            $previousYearSales = $this->percentagePreviousToSales(false);
            $currentYearSales = $this->percentageActualToSales(false);

            if ($currentYearSales < $previousYearSales) {
                return 'Lower';
            } elseif ($currentYearSales > $previousYearSales) {
                return 'Higher';
            } elseif ($currentYearSales >= $this->lowOfSalesRangeInPercent(false) ||
                $currentYearSales <= $this->highOfSalesRangeInPercent(false)) {
                return '=';
            }
        }

        return '';
    }

    public function percentageActualToSalesStyle()
    {
        $currentYear = $this->percentageActualToSales(false);
        $previousYear = $this->percentagePreviousToSales(false);

        $accountId = $this->account->id;

        if (in_array($accountId, [AccountId::GROSS_PROFIT, AccountId::OPERATING_INCOME, AccountId::NET_INCOME])) {
            if ($currentYear < $previousYear) {
                return $this->redStyle;
            } elseif ($currentYear > $previousYear) {
                return $this->purpleStyle;
            }
        } else {
            if ($currentYear < $previousYear) {
                return $this->purpleStyle;
            } elseif ($currentYear > $previousYear) {
                return $this->redStyle;
            }
        }
    }

    public function lowOrHighOfSalesStyle()
    {
        $currentYear = $this->percentageActualToSales(false);
        $previousYear = $this->percentagePreviousToSales(false);

        $accountId = $this->account->id;

        if (in_array($accountId, [AccountId::GROSS_PROFIT, AccountId::OPERATING_INCOME, AccountId::NET_INCOME])) {
            if ($currentYear < $previousYear) {
                return $this->redStyle;
            } elseif ($currentYear > $previousYear) {
                return $this->greenStyle;
            }
        } else {
            if ($currentYear < $previousYear) {
                return $this->greenStyle;
            } elseif ($currentYear > $previousYear) {
                return $this->redStyle;
            }
        }
    }
}