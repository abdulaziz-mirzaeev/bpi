<?php
/**
 * @var \app\models\Account[] $accounts
 */
?>

<h1><i>Under Development</i></h1>

<table class="table table-bordered">
    <thead class="text-center">
        <tr>
            <th scope="col">P&L Statement</th>

            <?php foreach ($data as $date => $subset): ?>
                <?php foreach ($subset as $type => $array): ?>
                    <th scope="col">
                        <?php echo Yii::$app->formatter->asDate($date, 'php:M-Y'); ?>
                        <span class="d-block"><?php echo Yii::$app->formatter->asDate($date, 'php:Y'); ?></span>
                        <span class="d-block"><?php echo $type; ?></span>
                    </th>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($accounts as $account): ?>
        <tr>
            <td><?php echo $account->name; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
