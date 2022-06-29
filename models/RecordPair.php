<?php


namespace app\models;


use Yii;

class RecordPair
{
    public Record $actual;
    public Record $plan;
    public Account $account;
    public ReportR7 $model;

    /**
     * RecordPair constructor.
     * @param Record $actual
     * @param Record $plan
     * @param int $account
     * @param ReportR7 $model
     */
    public function __construct(Record $actual, Record $plan, int $account, $model)
    {
        $this->actual = $actual;
        $this->plan = $plan;
        $this->account = Account::getById($account);
        $this->model = $model;
    }

    public function percentageA2P($formatting = true)
    {
        if ($this->plan->value == 0) {
            return "#DIV/0!";
        }

        $value = $this->actual->value / $this->plan->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
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
        $value = $this->actual->value - $this->plan->value;
        return $formatting ? Yii::$app->formatter->asDecimal($value, 0) : $value;
    }

    public function percentageA2NetSales($formatting = true)
    {
        $value = $this->actual->value / $this->model->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function percentageP2NetSales($formatting = true)
    {
        $value = $this->plan->value / $this->model->plan->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function percentNetSalesDifferenceA2P($formatting = true)
    {
        $actual = $this->percentageA2NetSales(false);
        $plan = $this->percentageP2NetSales(false);

        return $formatting ? Yii::$app->formatter->asPercent($actual - $plan) : $actual - $plan;
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