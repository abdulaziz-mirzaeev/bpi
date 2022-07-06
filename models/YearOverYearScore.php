<?php


namespace app\models;


use Tightenco\Collect\Support\Collection;
use yii\base\Model;

class YearOverYearScore extends ActualToPlanScore
{
    protected static array $data = [
        [
            'criteria_id' => 1,
            'date' => '2022-05',
            'value' => 6,
        ],
        [
            'criteria_id' => 2,
            'date' => '2022-05-01',
            'value' => 8,
        ],
        [
            'criteria_id' => 3,
            'date' => '2022-05-01',
            'value' => 10,
        ],
        [
            'criteria_id' => 4,
            'date' => '2022-05-01',
            'value' => -8,
        ],
        [
            'criteria_id' => 5,
            'date' => '2022-05-01',
            'value' => -4,
        ],
        [
            'criteria_id' => 6,
            'date' => '2022-05-01',
            'value' => -6,
        ],
        [
            'criteria_id' => 7,
            'date' => '2022-05-01',
            'value' => -8,
        ],
        [
            'criteria_id' => 8,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 9,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 10,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 11,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 12,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 13,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 14,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 15,
            'date' => '2022-05-01',
            'value' => -8,
        ],
        [
            'criteria_id' => 16,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 17,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 18,
            'date' => '2022-05-01',
            'value' => -10,
        ],
    ];

    public function getCriteria()
    {
        return YearOverYearScoreCriteria::getById($this->criteria_id);
    }
}