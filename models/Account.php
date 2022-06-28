<?php

namespace app\models;

use app\helpers\Data;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $display_name
 * @property int|null $visible
 * @property int|null $type
 * @property int|null $statement
 */
class Account extends Model
{
    public int $id;
    public string $name;
    public string $display_name;
    public int $visible = 1;
    public int $type;
    public string $statement;

    const VISIBLE_FALSE = 0;
    const VISIBLE_TRUE = 1;

    const NET_SALES_ID = 5;
    const GROSS_PROFIT_ID = 16;
    const OPERATING_INCOME_ID = 29;
    const NET_INCOME_ID = 37;

    const TYPE_INCOME = 1;
    const TYPE_EXPENSE = 0;


//    /**
//     * {@inheritdoc}
//     */
    public function rules()
    {
        return [
            [['visible', 'type'], 'integer'],
            [['name', 'display_name'], 'string', 'max' => 255],
        ];
    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'name' => 'Name',
//            'display_name' => 'Display Name',
//            'visible' => 'Visible',
//            'type' => 'Type',
//        ];
//    }

    public function getDisplayLabel(): string
    {
        return $this->display_name ?? $this->name;
    }

    public static function getById(int $id): Account
    {
        return collect(Data::getAllAccounts())->first(fn(Account $a) => $a->id === $id);
    }
}
