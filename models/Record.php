<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "records".
 *
 * @property int $id
 * @property int|null $account_id
 * @property float|null $value
 * @property string|null $date
 * @property string|null $type
 *
 * @property Account $account
 */
class Record extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['value'], 'number'],
            [['date'], 'safe'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'value' => 'Value',
            'date' => 'Date',
            'type' => 'Type',
        ];
    }

    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }

    public function getValueF()
    {
        return Yii::$app->formatter->asDecimal($this->value, 0);
    }

    public static function percentageOfActualToPlan(Record $actual, Record $planned, $formatting = true)
    {
        if ($planned->value == 0) {
            return '#DIV/0!';
        }
        $number = $actual->value / $planned->value;

        return $formatting ? round($number * 100) . '%' : $number;
    }

    public static function differenceOfActualToPlan(Record $actual, Record $planned)
    {
        return Yii::$app->formatter->asDecimal($actual->value - $planned->value, 0);
    }

    public static function percentageOfXtoY($actual, $planned, $formatting = true)
    {
        if ($planned == 0) {
            return '#DIV/0!';
        }
        $number = $actual / $planned;

        return $formatting ? round($number * 100) . '%' : $number;
    }
}
