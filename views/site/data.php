<?php
/**
 * @var \app\models\Account[] $pl_accounts
 * @var \app\models\Account[] $bSh_accounts
 * @var \Tightenco\Collect\Support\Collection $records
 */

use app\enums\AccountStatement;

$this->title = 'Data'
?>

<h1><i>Under Development</i></h1>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="text-center">
        <tr>
            <th scope="col"></th>

            <?php foreach ($data as $group): ?>
                <th scope="col">
                    <?php echo Yii::$app->formatter->asDate($group['date'], 'php:M'); ?>
                    <span class="d-block"><?php echo Yii::$app->formatter->asDate($group['date'], 'php:Y'); ?></span>
                    <span class="d-block"><?php echo $group['type']; ?></span>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="fw-bold text-decoration-underline">
                <?php echo AccountStatement::PROFIT_OR_LOSS; ?>
            </td>

            <?php foreach ($data as $group): ?>
                <td class="bg-light border-light"></td>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($pl_accounts as $account): ?>
            <tr>
                <td class="fs-6"><?php echo $account->name; ?></td>
                <?php /** @var \app\models\Record $record */ ?>
                <?php foreach ($records->where('account_id', $account->id)->all() as $record): ?>
                    <td class="text-end"><?php echo $record->getValueF(); ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td class="fw-bold text-decoration-underline">
                <?php echo AccountStatement::BALANCE_SHEET; ?>
            </td>

            <?php foreach ($data as $group): ?>
                <td class="bg-light border-light"></td>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($bSh_accounts as $account): ?>
            <tr>
                <td class="fs-6"><?php echo $account->name; ?></td>
                <?php foreach ($data as $group): ?>
                    <?php $record = $records->first(function ($item, $key) use ($account, $group) {
                        return $item->account_id == $account->id && $item->type == $group['type'] && $item->date == $group['date'];
                    }); ?>
                    <td class="text-end"><?php echo $record ? $record->getValueF() : ''; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
