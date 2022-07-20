<?php


namespace app\models;


abstract class RecordPairPL
{
    public Record $actual;
    public Record $comparable;
    public Account $account;
    public ReportPL $model;

    /**
     * RecordPair constructor.
     * @param Record $actual
     * @param Record $plan
     * @param int $account
     * @param ReportPL $model
     */
    public function __construct(Record $actual, Record $plan, int $account, ReportPL $model)
    {
        $this->actual = $actual;
        $this->comparable = $plan;
        $this->account = Account::getById($account);
        $this->model = $model;
    }

    public string $redStyle = "background-color: red; color: #fff;";
    public string $purpleStyle = "background-color: var(--bs-purple); color: #fff;";
    public string $yellowStyle = "background-color: var(--bs-warning); color: #000;";
    public string $greenStyle = "background-color: var(--bs-success); color: #fff;";
}