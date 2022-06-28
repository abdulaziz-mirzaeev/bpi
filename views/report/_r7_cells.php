<?php
/**
 * @var \app\models\RecordPair[] $recordPairs
 */
?>

<?php foreach ($recordPairs as $recordGroup): ?>
    <tr>
        <td><?php echo $recordGroup->account->name; ?></td>
        <td><?php echo $recordGroup->actual->valueF; ?></td>
        <td><?php echo $recordGroup->plan->valueF; ?></td>
        <td><?php echo $recordGroup->percentageA2P(); ?></td>
        <td><?php echo $recordGroup->dollarDifferenceA2P(); ?></td>
        <td><?php echo $recordGroup->percentageA2NetSales(); ?></td>
        <td><?php echo $recordGroup->percentageP2NetSales(); ?></td>
        <td><?php echo $recordGroup->percentNetSalesDifferenceA2P(); ?></td>
        <td><?php echo $recordGroup->actualNetSalesEquiv100(); ?></td>
        <td><?php echo $recordGroup->planNetSalesEquiv100(); ?></td>
        <td><?php echo $recordGroup->equiv100DifferenceA2P(); ?></td>
    </tr>
<?php endforeach; ?>
