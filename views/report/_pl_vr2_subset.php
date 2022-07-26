<?php
/**
 * @var \app\models\RecordPairPL_VR2[] $recordPairs
 */

use app\enums\AccountId;
use yii\bootstrap5\Html;

?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <?php $accountId = $recordGroup->account->id; ?>
    <tr data-account-id="<?php echo $accountId; ?>">
        <td class="<?php echo $recordGroup->account->getClass(); ?>text-nowrap">
            <?php echo $recordGroup->account->name; ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->comparable->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->actual->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->actualChangeInDollars(); ?></td>
        <td class="text-center"><?php echo $recordGroup->actualChangeInPercent(true, 1);?></td>
        <td class="text-end"><?php echo $recordGroup->percentagePreviousToSales(true, 1); ?></td>
        <td class="text-end" style="<?php echo $recordGroup->percentageActualToSalesStyle(); ?>">
            <?php echo $recordGroup->percentageActualToSales(true, 1); ?>
        </td>

        <?php if ($accountId == AccountId::NET_SALES): ?>
            <td></td>
            <td class="text-end"><?php echo $recordGroup->model->getLowOfChange(); ?></td>
            <td class="text-end"><?php echo $recordGroup->model->getHighOfChange(); ?></td>
        <?php else: ?>
            <td class="text-end"><?php echo $recordGroup->additionalSpendOfSalesInPercent(); ?></td>
            <td class="text-end"><?php echo $recordGroup->lowOfSalesRangeInPercent(true, 1); ?></td>
            <td class="text-end"><?php echo $recordGroup->highOfSalesRangeInPercent(true, 1); ?></td>
        <?php endif; ?>

        <td class="text-center" style="<?php echo $recordGroup->lowOrHighOfSalesStyle(); ?>">
            <?php echo $recordGroup->lowOrHighOfSales(); ?>
        </td>

        <td class="text-center">
            <?php echo Html::checkbox("areaToAct[$accountId]", false, [
                    'class' => 'form-check-input area-to-act',
                    'data-account-id' => $accountId,
            ]); ?>
        </td>
    </tr>
<?php endforeach; ?>
