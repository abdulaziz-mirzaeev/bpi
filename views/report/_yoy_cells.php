<?php
/**
 * @var \app\models\RecordPairYOY[] $recordPairs
 */
?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <tr>
        <td class="<?php echo $recordGroup->account->getClass(); ?> text-nowrap">
            <?php echo $recordGroup->account->getDisplayLabel(); ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->comparable->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->actual->valueF; ?></td>
        <td class="text-end"><?php echo $recordGroup->percentagePrevioustoSales(); ?></td>
        <td class="text-end"><?php echo $recordGroup->percentageActualtoSales(); ?></td>
        <td class="text-end" style="<?php echo $recordGroup->salesPercentageDifferenceOfCurrentAndPreviousStyle(); ?>">
            <?php echo $recordGroup->salesPercentageDifferenceOfCurrentAndPrevious();?>
        </td>
        <td class="text-end" style="<?php echo $recordGroup->actualChangeInDollarsStyle(); ?>">
            <?php echo $recordGroup->actualChangeInDollars(); ?>
        </td>
        <td class="text-end"><?php echo $recordGroup->actualChangeInPercent(); ?></td>
        <td class="text-end"><?php echo $recordGroup->actualToSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->previousToSalesEquiv100(); ?></td>
        <td class="text-end"><?php echo $recordGroup->equiv100DifferenceActualToPrevious(); ?></td>
        <td class="text-end"><?php echo $recordGroup->yearOverYearDifferenceStatus(); ?></td>
    </tr>
<?php endforeach; ?>
