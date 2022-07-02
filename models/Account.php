<?php

namespace app\models;

use app\enums\AccountId;
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

    public static array $directCostsSubset = [
        AccountId::TOTAL_SALES_COMMISSION,
        AccountId::TOTAL_DIRECT_LABOR,
        AccountId::TOTAL_SUBCONTRACTORS,
        AccountId::TOTAL_MATERIALS,
        AccountId::TOTAL_EQUIPMENT,
        AccountId::TOTAL_DIRECT_TRANSPORTATION,
        AccountId::TOTAL_DIRECT_TRAVEL,
        AccountId::UNIQUE_COGS_ITEM_B_TO_BUSINESS,
        AccountId::TOTAL_OTHER_DIRECT_COSTS,
    ];

    public static array $operatingCostsSubset = [
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
    ];

    public static array $othersSubset = [
        AccountId::TOTAL_OTHER_INCOME,
        AccountId::TOTAL_OTHER_EXPENSES,
        AccountId::TOTAL_OWNERS_COMPENSATION,
        AccountId::TOTAL_INTEREST,
        AccountId::TOTAL_TAXES,
        AccountId::TOTAL_DEP_AND_AMM_EXPENSE,
    ];

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
