<?php


namespace app\models;


use app\enums\AccountId;
use app\helpers\Tools;
use Yii;

class RecordPairA2P extends RecordPairPL
{
    public function percentageA2P($formatting = true, $decimals = 0)
    {
        if ($this->comparable->value == 0) {
            return "#DIV/0!";
        }

        $value = $this->actual->value / $this->comparable->value;
        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function percentageA2PStyle()
    {
        $value = $this->percentageA2P(false);

        if (!is_numeric($value)) {
            return '';
        }

        $redStyle = "background-color: red; color: #fff;";
        $purpleStyle = "background-color: var(--bs-purple); color: #fff;";
        $yellowStyle = "background-color: var(--bs-warning); color: #000;";
        $greenStyle = "background-color: var(--bs-success); color: #fff;";

        $accountType = $this->account->type;

        if ($accountType == Account::TYPE_INCOME) {
            if ($value < 0.8 ) {
                return $redStyle;
            } elseif ($value >= 0.8 && $value < 0.95 ) {
                return $yellowStyle;
            } elseif ($value >= 0.95 && $value < 1.1) {
                return $greenStyle;
            } elseif ($value >= 1.1) {
                return $purpleStyle;
            }
        } elseif ($accountType == Account::TYPE_EXPENSE) {
            if ($value < 0.8 ) {
                return $purpleStyle;
            } elseif ($value >= 0.8 && $value < 0.95 ) {
                return $yellowStyle;
            } elseif ($value >= 0.95 && $value < 1.1) {
                return $greenStyle;
            } elseif ($value >= 1.1) {
                return $redStyle;
            }
        }
    }

    public function dollarDifferenceA2P($formatting = true)
    {
        $value = $this->actual->value - $this->comparable->value;
        return $formatting ? Yii::$app->formatter->asDecimal($value, 0) : $value;
    }

    public function dollarDifferenceA2PStyle()
    {
        $value = $this->dollarDifferenceA2P(false);

        $redStyle = "background-color: red; color: #fff;";

        $accountId = $this->account->id;

        $othersSubset = Account::$othersSubset;
        array_shift($othersSubset);
        array_shift($othersSubset);

        if ($accountId == AccountId::NET_SALES) {
            if ($value < Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.NET_SALES')) {
                return $redStyle;
            }
        } else if (in_array($accountId, Account::$directCostsSubset)) {
            if ($value > Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.DIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, Account::$operatingCostsSubset)) {
            if ($value > Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.INDIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, $othersSubset)) {
            if ($value > Tools::getParam('company.a2p_p&l.thresholds.dollarDifference.NET_NONOPERATING_COSTS')) {
                return $redStyle;
            }
        }

        return '';
    }

    public function percentageA2NetSales($formatting = true, $decimals = 0)
    {
        $value = $this->actual->value / $this->model->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function percentageP2NetSales($formatting = true, $decimals = 0)
    {
        $value = $this->comparable->value / $this->model->comparable->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value, $decimals) : $value;
    }

    public function percentNetSalesDifferenceA2P($formatting = true, $decimals = 0)
    {
        $actual = $this->percentageA2NetSales(false);
        $plan = $this->percentageP2NetSales(false);

        return $formatting ? Yii::$app->formatter->asPercent($actual - $plan, $decimals) : $actual - $plan;
    }

    public function actualNetSalesEquiv100()
    {
        return Yii::$app->formatter->asDecimal($this->percentageA2NetSales(false) * 100, 0);
    }

    public function planNetSalesEquiv100()
    {
        return Yii::$app->formatter->asDecimal($this->percentageP2NetSales(false) * 100, 0);
    }

    public function equiv100DifferenceA2P()
    {
        return Yii::$app->formatter->asDecimal($this->percentNetSalesDifferenceA2P(false) * 100, 0);
    }
}