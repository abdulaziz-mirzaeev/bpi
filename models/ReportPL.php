<?php


namespace app\models;


use app\enums\AccountId;
use app\helpers\Tools;

abstract class ReportPL
{

    public function getRecordsByAccounts(array $accountIds)
    {
        return collect($this->getRecords())->filter(function ($recordPair) use ($accountIds) {
            return in_array($recordPair->account->id, $accountIds);
        })->all();
    }

    /**
     * @param int $accountId
     * @return RecordPairA2P|RecordPairYOY
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