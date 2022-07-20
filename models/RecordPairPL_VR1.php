<?php
namespace app\models;

use app\enums\AccountId;
use Yii;


class RecordPairPL_VR1 extends RecordPairA2P
{
    public function getLowPercentageOfPlan($formatting = true, $decimals = 0)
    {
        $value = $this->percentageP2NetSales(false) * $this->model->lowOfPlan;

        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function getHighPercentageOfPlan($formatting = true, $decimals = 0)
    {
        $value = $this->percentageP2NetSales(false) * $this->model->highOfPlan;

        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function getPercentOfSalesAlignment()
    {
        $actualToSales = $this->percentageA2NetSales(false);
        $planToSales = $this->percentageP2NetSales(false);

        if ($actualToSales < $planToSales) {
            return 'Below';
        } elseif ($actualToSales > $planToSales) {
            return 'Above';
        } elseif ($actualToSales >= $this->model->lowOfPlan || $actualToSales <= $this->model->highOfPlan) {
            return 'Within';
        }
    }

    public function getPercentOfSalesAlignmentStyle()
    {
        $accountId = $this->account->id;

        if (in_array($accountId, [AccountId::GROSS_PROFIT, AccountId::OPERATING_INCOME, AccountId::NET_INCOME])) {
            if ($this->percentageA2NetSales(false) < $this->percentageP2NetSales(false)) {
                return $this->redStyle;
            } elseif ($this->percentageA2NetSales(false) > $this->percentageP2NetSales(false)) {
                return $this->greenStyle;
            }
        } else {
            if ($this->percentageA2NetSales(false) < $this->percentageP2NetSales(false)) {
                return $this->greenStyle;
            } elseif ($this->percentageA2NetSales(false) > $this->percentageP2NetSales(false)) {
                return $this->redStyle;
            }
        }

        return '';
    }

    public function getA2NetSalesStyle()
    {
        $accountId = $this->account->id;

        if (in_array($accountId, [AccountId::GROSS_PROFIT, AccountId::OPERATING_INCOME, AccountId::NET_INCOME])) {
            if ($this->percentageA2NetSales(false) < $this->percentageP2NetSales(false)) {
                return $this->redStyle;
            } elseif ($this->percentageA2NetSales(false) > $this->percentageP2NetSales(false)) {
                return $this->purpleStyle;
            }
        } else {
            if ($this->percentageA2NetSales(false) < $this->percentageP2NetSales(false)) {
                return $this->purpleStyle;
            } elseif ($this->percentageA2NetSales(false) > $this->percentageP2NetSales(false)) {
                return $this->redStyle;
            }
        }

        return '';
    }

    public function inOrOutOfPlan()
    {
        if ($this->account->id == AccountId::NET_SALES) {
            $percentageA2P = $this->percentageA2P(false);
            if ($percentageA2P > $this->model->lowOfPlan && $percentageA2P == $this->model->highOfPlan) {
                return 'In';
            } else {
                return 'Out';
            }
        }

        return '';
    }
}