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
        return $this->getRecordsByAccounts([
            AccountId::TOTAL_SALES_COMMISSION,
            AccountId::TOTAL_DIRECT_LABOR,
            AccountId::TOTAL_SUBCONTRACTORS,
            AccountId::TOTAL_MATERIALS,
            AccountId::TOTAL_EQUIPMENT,
            AccountId::TOTAL_DIRECT_TRANSPORTATION,
            AccountId::TOTAL_DIRECT_TRAVEL,
            AccountId::UNIQUE_COGS_ITEM_B_TO_BUSINESS,
            AccountId::TOTAL_OTHER_DIRECT_COSTS,
            AccountId::COGS,
        ]);
    }

    public function getGrossProfitSubset()
    {
        return $this->getRecordsByAccounts([AccountId::GROSS_PROFIT]);
    }

    public function getOperatingCostsSubset()
    {
        return $this->getRecordsByAccounts([
            AccountId::TOTAL_MARKETING_INVESTMENT,
            AccountId::TOTAL_TRAVEL_AND_ENTERTAINMENT,
            AccountId::TOTAL_OFFICE_EXPENSE,
            AccountId::TOTAL_OFFICE_PAYROLL,
            AccountId::TOTAL_INSURANCE,
            AccountId::TOTAL_OUTSIDE_FEES,
            AccountId::TOTAL_PROPERTY_EXPENSE,
            AccountId::TOTAL_UTILIITIES,
            AccountId::UNIQUE_SG_AND_A_ITEM_C_TO_BUSINESS,
            AccountId::UNIQUE_SG_AND_A_ITEM_D_TO_BUSINESS,
            AccountId::TOTAL_MISCELLANEOUS_EXPENSE,
            AccountId::TOTAL_SG_AND_A_EXPENSE,
        ]);
    }

    public function getOperatingIncomeSubset()
    {
        return $this->getRecordsByAccounts([AccountId::OPERATING_INCOME]);
    }

    public function getOthersSubset()
    {
        return $this->getRecordsByAccounts([
            AccountId::TOTAL_OTHER_INCOME,
            AccountId::TOTAL_OTHER_EXPENSES,
            AccountId::TOTAL_OWNERS_COMPENSATION,
            AccountId::TOTAL_INTEREST,
            AccountId::TOTAL_TAXES,
            AccountId::TOTAL_DEP_AND_AMM_EXPENSE,
            AccountId::TOTAL_NONOPERATING_EXPENSE_LESS_NONOPERATING_INCOME,
        ]);
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