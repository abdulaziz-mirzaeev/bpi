<?php


namespace app\models;


use Tightenco\Collect\Support\Collection;

class ActualToPlanScoreCriteria
{
    private static array $data = [
        [
            'id' => 1,
            'code' => '1.0',
            'name' => 'Net Sales Score',
            'description' => ' (I.e., Is current sales too low?)',
        ],
        [
            'id' => 2,
            'code' => '1.6',
            'name' => 'N.S. Actual % of Plan Score',
            'description' => '(I.e., Did you meet N.S. plan?)',
        ],
        [
            'id' => 3,
            'code' => '1.7',
            'name' => 'N.S. Actual $ to Plan Score',
            'description' => '(I.e., How close did actual N.S. come to plan?)',
        ],
        [
            'id' => 4,
            'code' => '2.0',
            'name' => 'G.P. Score',
            'description' => '(I.e., Is current Gross Profit too low?)',
        ],
        [
            'id' => 5,
            'code' => '2.2.1',
            'name' => 'G.P. % of Sales Score',
            'description' => '(I.e., Is current G.P. % of Sales too low?)'
        ],
        [
            'id' => 6,
            'code' => '2.5',
            'name' => 'COGS Actual % of Sales Score',
            'description' =>'(I.e., Are Direct Costs higher than plan?)',
        ],
        [
            'id' => 7,
            'code' => '2.6',
            'name' => 'G.P. Actual % of Plan Score',
            'description' => '(I.e., Did you meet G.P. plan?)',
        ],
        [
            'id' => 8,
            'code' => '2.7',
            'name' => 'G.P. Actual $ to Plan Score',
            'description' => '(I.e., How close did actual G.P. come to plan?)',
        ],
        [
            'id' => 9,
            'code' => '3.0',
            'name' => 'O.I. Score',
            'description' => '(I.e., Is current Operating Income too low?)',
        ],
        [
            'id' => 10,
            'code' => '3.2.1',
            'name' => 'O.I. % of Sales Score',
            'description' => '(I.e., Is current Operating Income % of Sales too low?)'
        ],
        [
            'id' => 11,
            'code' => '3.5',
            'name' => 'SG&A Actual % of Sales Score',
            'description' => '(I.e., Are Indirect Costs higher than plan?)',
        ],
        [
            'id' => 12,
            'code' => '3.6',
            'name' => 'O.I. Actual % of Plan Score',
            'description' => '(I.e., Did you meet O.I. plan?)',
        ],
        [
            'id' => 13,
            'code' => '3.7', 'name' => 'O.I. Actual $ to Plan Score',
            'description' => '(I.e., How close did actual O.I. come to plan?)',
        ],
        [
            'id' => 14,
            'code' => '4.0',
            'name' => 'N.I. Score',
            'description' => '(I.e., Is current Net Income too low?)',
        ],
        [
            'id' => 15,
            'code' => '4.2.1',
            'name' => 'N.I. % of Sales Score',
            'description' => '(I.e., Is current N.I. % of Sales too low?)',
        ],
        [
            'id' => 16,
            'code' => '4.3',
            'name' => 'N.I. % Change Score',
            'description' => '(I.e., How much did N.I. grow?)',
        ],
        [
            'id' => 17,
            'code' => '4.5',
            'name' => 'Nonop Actual % of Plan Score',
            'description' => '(I.e., Are Nonoperating Costs higher than plan?)',
        ],
        [
            'id' => 18,
            'code' => '4.6',
            'name' => 'N.I. Actual % of Plan Score',
            'description' => '(I.e., Did you meet N.I. plan?)',
        ],
        [
            'id' => 19,
            'code' => '4.7',
            'name' => 'N.I. Actual $ to Plan Score',
            'description' => '(I.e., How close did actual N.I. come to plan?)',
        ],
    ];

    public int $id;
    public string $code;
    public string $name;
    public string $description;


    public static function getAll(): Collection
    {
        $criteria = [];
        foreach (self::$data as $item) {
            $criterion = new static();
            $criterion->id = $item['id'];
            $criterion->code = $item['code'];
            $criterion->name = $item['name'];
            $criterion->description = $item['description'];

            $criteria[] = $criterion;
        }

        return collect($criteria);
    }

    public static function getByCode(string $code): self
    {
        return self::getAll()->firstWhere('code', $code);
    }

    public static function getById(int $id): self
    {
        return self::getAll()->firstWhere('id', $id);
    }

    public function getFullName()
    {
        return $this->code . ' ' . $this->name . ' ' . $this->description;
    }

}