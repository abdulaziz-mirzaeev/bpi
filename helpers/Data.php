<?php


namespace app\helpers;


use app\models\Account;
use yii\helpers\ArrayHelper;

class Data
{
    /**
     * @return Account[]
     */
    public static function getAccounts()
    {
        $data = require __DIR__ . '/data/accounts.php';

        $accounts = [];

        foreach ($data as $item) {
            $account = new Account();

            $account->id = $item['id'];

            $account->name = $item['name'];

            if ($item['display_name']) {
                $account->display_name = $item['display_name'];
            }

            if (ArrayHelper::keyExists('visible', $item)) {
                $account->visible = $item['visible'];
            }

            if (ArrayHelper::keyExists('type', $item)) {
                $account->type = $item['type'];
            }
            $accounts[] = $account;
        }

        return $accounts;
    }
}