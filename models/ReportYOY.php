<?php


namespace app\models;


use app\enums\AccountStatement;
use app\enums\RecordType;
use app\exceptions\RecordsNotFoundForDateAndTypeException;

class ReportYOY extends ReportPL
{
    public Dataset $actual;
    public Dataset $previous;
    public $date;
    public $datePrevious;

    /**
     * ReportYOY constructor.
     * @param $date
     * @throws RecordsNotFoundForDateAndTypeException
     */
    public function __construct($date)
    {
        $this->date = date('Y-m', strtotime($date));
        $this->datePrevious = date('Y-m', strtotime('-1 year', strtotime($this->date)));

        $this->actual = new Dataset($this->date, RecordType::ACTUAL);
        $this->previous = new Dataset($this->datePrevious, RecordType::ACTUAL);
    }

    public function getRecords()
    {
        return collect($this->actual->records)
            ->merge($this->previous->records)
            ->filter(function (Record $record) {
                return $record->account->visible === Account::VISIBLE_TRUE &&
                    $record->account->statement === AccountStatement::PROFIT_OR_LOSS;
            })
            ->groupBy('account_id')
            ->map(function ($recordGroup, $accountId) {
                return new RecordPairYOY(
                    $recordGroup->first(fn(Record $item) => $item->getDateF() === $this->date),
                    $recordGroup->first(fn(Record $item) => $item->getDateF() === $this->datePrevious),
                    $accountId,
                    $this,
                );
            })
            ->all();
    }

}