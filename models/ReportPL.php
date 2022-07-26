<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\RecordType;
use app\helpers\Tools;
use yii\base\InvalidConfigException;

abstract class ReportPL
{
    public Dataset $actual;
    public Dataset $comparable;
    public $date;

    public $recordPairClass;
    public $comparableType;

    public function init()
    {
        if ($this->recordPairClass === null) {
            throw new InvalidConfigException('The "recordPairClass" property must be set.');
        }
    }

    public function getRecords()
    {
        return collect($this->actual->records)
            ->merge($this->comparable->records)
            ->filter(function (Record $record) {
                return $record->account->visible === Account::VISIBLE_TRUE &&
                    $record->account->statement === AccountStatement::PROFIT_OR_LOSS;
            })
            ->groupBy('account_id')
            ->map(function ($recordGroup, $key) {
                return new $this->recordPairClass(
                    $recordGroup->first(fn(Record $item) => $item->type === RecordType::ACTUAL),
                    $recordGroup->first(fn(Record $item) => $item->type === $this->comparableType),
                    $key,
                    $this
                );
            })
            ->all();
    }

    public function getRecordsByAccounts(array $accountIds)
    {
        return collect($this->getRecords())->filter(function ($recordPair) use ($accountIds) {
            return in_array($recordPair->account->id, $accountIds);
        })->all();
    }

    /**
     * @param int $accountId
     * @return RecordPairA2P|RecordPairYOY|RecordPairPL_VR2|RecordPairPL_VR1
     */
    public function getRecordByAccount(int $accountId)
    {
        return collect($this->getRecords())->filter(function ($recordPair) use ($accountId) {
            return $recordPair->account->id == $accountId;
        })->first();
    }

    public function getNetSalesSubset()
    {
        return $this->getRecordsByAccounts([AccountId::NET_SALES]);
    }

    public function getDirectCostsSubset()
    {
        return $this->getRecordsByAccounts(Account::$directCostsSubset);
    }

    public function getCOGSsubset()
    {
        return $this->getRecordsByAccounts([AccountId::COGS]);
    }

    public function getGrossProfitSubset()
    {
        return $this->getRecordsByAccounts([AccountId::GROSS_PROFIT]);
    }

    public function getOperatingCostsSubset()
    {
        return $this->getRecordsByAccounts(Account::$operatingCostsSubset);
    }

    public function getTotalSGnAExpenseSubset()
    {
        return $this->getRecordsByAccounts([AccountId::TOTAL_SG_AND_A_EXPENSE]);
    }

    public function getOperatingIncomeSubset()
    {
        return $this->getRecordsByAccounts([AccountId::OPERATING_INCOME]);
    }

    public function getOthersSubset()
    {
        return $this->getRecordsByAccounts(Account::$othersSubset);
    }

    public function getNetNonOperatingCostsSubset()
    {
        return $this->getRecordsByAccounts([AccountId::TOTAL_NONOPERATING_EXPENSE_LESS_NONOPERATING_INCOME]);
    }

    public function getNetIncomeSubset()
    {
        return $this->getRecordsByAccounts([AccountId::NET_INCOME]);
    }

    public function getDirectCostCogsSubset()
    {
        return [...$this->getDirectCostsSubset(), ...$this->getCOGSsubset()];
    }

    public function getOperatingCostsAndSGA()
    {
        return [...$this->getOperatingCostsSubset(), ...$this->getTotalSGnAExpenseSubset()];
    }

    protected function conditionalMessageByScore(int $score, array $messages, array $intervals = [
        [8, 10],
        [4, 6],
        [-2, 2],
        [-6, -4],
        [-10, -8],
    ]): string
    {
        foreach ($intervals as $i => [$low, $high]) {
            if (Tools::isBetween($score, $low, $high)) {
                return $messages[$i];
            }
        }

        return '';
    }

}