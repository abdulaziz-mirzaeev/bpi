<?php


namespace app\models;

/**
 * @property Record[] $records
 */

use app\enums\AccountId;
use app\enums\RecordType;
use app\exceptions\RecordsNotFoundForDateAndTypeException;
use app\helpers\Data;
use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\SluggableBehavior;
use yii\web\NotFoundHttpException;

class Dataset
{
    public $records;
    public $date;
    public $type;
    public $accounts;

    public function __construct($date, $type)
    {
        $this->date = Yii::$app->formatter->asDate($date, 'php:Y-m');
        $this->type = $type;

        $records = Record::find()
            ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $this->date])
            ->andWhere(['type' => $this->type])
            ->orderBy(['account_id' => SORT_ASC])
            ->all();

        if (empty($records)) {
            throw new RecordsNotFoundForDateAndTypeException(
                "Records for type {$type} and date {$date} not found!"
            );
        }

        $this->records = $records;
        $this->accounts = Data::getAllAccounts();
    }

    public function getNetSales(): Record
    {
        return collect($this->records)->firstWhere('account_id', Account::NET_SALES_ID);
    }

    public function getGrossProfit(): Record
    {
        return collect($this->records)->firstWhere('account_id', AccountId::GROSS_PROFIT);
    }

    public function getOperatingIncome(): Record
    {
        return collect($this->records)->firstWhere('account_id', AccountId::OPERATING_INCOME);
    }

    public function getNetIncome(): Record
    {
        return collect($this->records)->firstWhere('account_id', AccountId::NET_INCOME);
    }
}