<?php


namespace app\models;


use Tightenco\Collect\Support\Collection;
use yii\base\Model;

class ActualToPlanScore extends Model
{
    private static array $data = [
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
            'value' => 8,
        ],
        [
            'criteria_id' => 4,
            'date' => '2022-05-01',
            'value' => -8,
        ],
        [
            'criteria_id' => 5,
            'date' => '2022-05-01',
            'value' => -8,
        ],
        [
            'criteria_id' => 6,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 7,
            'date' => '2022-05-01',
            'value' => -10,
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
            'value' => -8,
        ],
        [
            'criteria_id' => 11,
            'date' => '2022-05-01',
            'value' => -6,
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
            'value' => -10,
        ],
        [
            'criteria_id' => 16,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 17,
            'date' => '2022-05-01',
            'value' => 2,
        ],
        [
            'criteria_id' => 18,
            'date' => '2022-05-01',
            'value' => -10,
        ],
        [
            'criteria_id' => 19,
            'date' => '2022-05-01',
            'value' => -10,
        ],

    ];

    public int $criteria_id;
    public string $date;
    public int $value;

    public static function getAll(): Collection
    {
        $scores = [];
        foreach (self::$data as $item) {
            $score = new static();
            $score->criteria_id = $item['criteria_id'];
            $score->date = $item['date'];
            $score->value = $item['value'];

            $scores[] = $score;
        }

        return collect($scores);
    }

    public static function getByDateAndCode(string $date, string $code): self
    {
        return self::getAll()->first(function (self $score) use ($date, $code) {
            return $score->getCriteria()->code == $code &&
                date('Y-m', strtotime($score->date)) == date('Y-m', strtotime($date));
        });
    }

    public function getCriteria()
    {
        return ActualToPlanScoreCriteria::getById($this->criteria_id);
    }
}