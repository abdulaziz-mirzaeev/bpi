<?php


namespace app\helpers;


use app\models\Account;
use yii\helpers\ArrayHelper;

class Data
{

    public static function getAllAccounts()
    {
        $pL = require __DIR__ . '/data/accounts.php';
        $bSh = require __DIR__ . '/data/balanceSheetAccounts.php';

        $data = ArrayHelper::merge($pL, $bSh);

        return self::formAccountInstances($data);
    }
    /**
     * @return Account[]
     */
    public static function getPLAccounts()
    {
        $data = require __DIR__ . '/data/accounts.php';

        return self::formAccountInstances($data);
    }

    public static function getBalanceSheetAccounts()
    {
        $data = require __DIR__ . '/data/balanceSheetAccounts.php';

        return self::formAccountInstances($data);
    }

    /**
     * @param $data
     * @return Account[]
     */
    private static function formAccountInstances($data): array
    {
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

            if (ArrayHelper::keyExists('statement', $item)) {
                $account->statement = $item['statement'];
            }

            $accounts[] = $account;
        }

        return $accounts;
    }
}