<?php


namespace app\models;


use app\enums\AccountId;
use app\enums\AccountStatement;
use app\enums\RecordType;
use Exception;
use Tightenco\Collect\Support\Collection;

class ReportR7
{
    public Dataset $actual;
    public Dataset $plan;
    public $date;

    public function __construct($date)
    {
        $this->date = date('Y-m', strtotime($date));

        try {
            $this->actual = new Dataset($this->date, RecordType::ACTUAL);
            $this->plan = new Dataset($this->date, RecordType::PLAN);
        } catch (Exception $e) {
            throw $e;
        }

    }


    /**
     * @return RecordPair[]
     */
    public function getRecords()
    {
        return collect($this->actual->records)
            ->merge($this->plan->records)
            ->filter(function (Record $record) {
                return $record->account->visible === Account::VISIBLE_TRUE &&
                    $record->account->statement === AccountStatement::PROFIT_OR_LOSS;
            })
            ->groupBy('account_id')
            ->map(function ($recordGroup, $key) {
                return new RecordPair(
                    $recordGroup->first(fn(Record $item) => $item->type === RecordType::ACTUAL),
                    $recordGroup->first(fn(Record $item) => $item->type === RecordType::PLAN),
                    $key,
                    $this
                );
            })
            ->all();
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

    public function getNetIncomeSubset()
    {
        return $this->getRecordsByAccounts([AccountId::NET_INCOME]);
    }

    /**
     * @param array $accountIds
     * @return Record[]
     */
    public function getRecordsByAccounts(array $accountIds)
    {
        return collect($this->getRecords())->filter(function (RecordPair $recordPair) use ($accountIds) {
            return in_array($recordPair->account->id, $accountIds);
        })->all();
    }

    public function getRecordByAccount(int $accountId): Record
    {
        return collect($this->getRecords())->filter(function (RecordPair $recordPair) use ($accountId) {
            return $recordPair->account->id == $accountId;
        })->first();
    }
}