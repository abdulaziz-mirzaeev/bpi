<?php


namespace app\models;


use app\enums\AccountId;
use app\helpers\Tools;
use Yii;

class RecordPairYOY extends RecordPairPL
{
    public Record $actual;
    public Record $previous;
    public Account $account;
    public ReportYOY $model;

    /**
     * RecordPairYOY constructor.
     * @param Record $actual
     * @param Record $previous
     * @param int $account
     * @param ReportYOY $model
     */
    public function __construct(Record $actual, Record $previous, int $account, ReportYOY $model)
    {
        $this->actual = $actual;
        $this->previous = $previous;
        $this->account = Account::getById($account);
        $this->model = $model;
    }

    public function percentageActualToSales($formatting = true)
    {
        $value = $this->actual->value / $this->model->actual->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function percentagePreviousToSales($formatting = true)
    {
        $value = $this->previous->value / $this->model->previous->getNetSales()->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function salesPercentageDifferenceOfCurrentAndPrevious($formatting = true)
    {
        $actual = $this->percentageActualToSales(false);
        $previous = $this->percentagePreviousToSales(false);

        return $formatting ? Yii::$app->formatter->asPercent($actual - $previous, 1) : $actual - $previous;
    }

    public function salesPercentageDifferenceOfCurrentAndPreviousStyle()
    {
        $value = $this->salesPercentageDifferenceOfCurrentAndPrevious(false);

        $redStyle = "background-color: red; color: #fff;";
        $accountId = $this->account->id;

        $othersSubset = Account::$othersSubset;
        array_shift($othersSubset);

        if (in_array($accountId, Account::$directCostsSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.DIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, Account::$operatingCostsSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.INDIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, $othersSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.salesPercentDifference.NET_NONOPERATING_COSTS')) {
                return $redStyle;
            }
        }

        return '';
    }

    public function actualChangeInDollars($formatting = true)
    {
        $actual = $this->actual->value;
        $previous = $this->previous->value;

        return $formatting ? Yii::$app->formatter->asDecimal($actual - $previous, 0) : $actual - $previous;
    }

    public function actualChangeInDollarsStyle()
    {
        $value = $this->actualChangeInDollars(false);

        $redStyle = "background-color: red; color: #fff;";
        $accountId = $this->account->id;

        $othersSubset = Account::$othersSubset;
        array_shift($othersSubset);

        if ($accountId == AccountId::NET_SALES) {
            if ($value < Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.NET_SALES')) {
                return $redStyle;
            }
        } else if (in_array($accountId, Account::$directCostsSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.DIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, Account::$operatingCostsSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.INDIRECT_COSTS')) {
                return $redStyle;
            }
        } else if (in_array($accountId, $othersSubset)) {
            if ($value > Tools::getParam('company.yoy_p&l.thresholds.actualChangeInDollars.NET_NONOPERATING_COSTS')) {
                return $redStyle;
            }
        }

        return '';
    }

    public function actualChangeInPercent($formatting = true)
    {
        if ($this->previous->value == 0) {
            return "#DIV/0!";
        }

        $value = $this->actualChangeInDollars(false) / $this->previous->value;
        return $formatting ? Yii::$app->formatter->asPercent($value) : $value;
    }

    public function actualToSalesEquiv100()
    {
        return Yii::$app->formatter->asDecimal($this->percentageActualToSales(false) * 100, 0);
    }

    public function previousToSalesEquiv100()
    {
        return Yii::$app->formatter->asDecimal($this->percentagePreviousToSales(false) * 100, 0);
    }

    public function equiv100DifferenceActualToPrevious()
    {
        return Yii::$app->formatter->asDecimal(
            $this->salesPercentageDifferenceOfCurrentAndPrevious(false) * 100, 0
        );
    }

    public function yearOverYearDifferenceStatus()
    {
        $accountId = $this->account->id;
        $actualChangeValue = $this->actualChangeInPercent(false);
        $equiv100Difference = $this->equiv100DifferenceActualToPrevious();

        $moreOrLessAccounts = [
            AccountId::COGS,
            AccountId::TOTAL_SG_AND_A_EXPENSE,
            AccountId::TOTAL_NONOPERATING_EXPENSE_LESS_NONOPERATING_INCOME,
            ...Account::$directCostsSubset,
            ...Account::$operatingCostsSubset,
            ...Account::$othersSubset,
        ];

        $betterOrWorseAccounts = [
            AccountId::NET_SALES,
            AccountId::GROSS_PROFIT,
            AccountId::OPERATING_INCOME,
            AccountId::NET_INCOME
        ];

        if (in_array($accountId, $betterOrWorseAccounts)) {
            if (!is_numeric($actualChangeValue)) {
                return '';
            }
            return $actualChangeValue > 0 ? 'better' : 'worse';
        }

        if (in_array($accountId, $moreOrLessAccounts)) {
            if (!is_numeric($equiv100Difference)) {
                return '';
            }
            return $equiv100Difference > 0 ? 'more' : 'less';
        }

    }
}