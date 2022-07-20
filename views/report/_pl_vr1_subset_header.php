<?php
/**
 * @var \app\models\ReportPL_VR1 $model
 * @var \yii\web\View $this
 * @var string $name
 */

use app\enums\RecordType;

$date = Yii::$app->formatter->asDate($model->date, 'php:Y');

$actualYearLabel = RecordType::ACTUAL;
$planYearLabel = RecordType::PLAN;
?>

<tbody class="fw-light">
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Out Low</td>
        <td>Out High</td>
        <td>% of Sales</td>
        <td></td>
    </tr>
    <tr class="border-bottom border-1 border-dark">
        <th scope="row"><?php echo $name; ?></th>
        <td class="fw-light text-nowrap"><?php echo $date . ' ' . $planYearLabel; ?></td>
        <td class="fw-light text-nowrap"><?php echo $date . ' ' . $actualYearLabel; ?></td>
        <td class="fw-light text-nowrap">P % of Sales</td>
        <td class="fw-light text-nowrap">A % of Sales</td>
        <td class="fw-light text-nowrap">Actual - Plan</td>
        <td class="fw-light text-nowrap">A % of Plan</td>
        <td class="table-primary text-end"><?php echo $model->getLowOfPlan(); ?></td>
        <td class="table-primary text-end"><?php echo $model->getHighOfPlan(); ?></td>
        <td class="text-center">Alignment</td>
        <td class="text-center">Area to Act</td>
    </tr>
</tbody>
