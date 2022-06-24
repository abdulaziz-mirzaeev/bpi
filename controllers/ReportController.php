<?php


namespace app\controllers;

use app\enums\RecordType;
use app\helpers\Data;
use app\helpers\Tools;
use app\models\Account;
use app\models\Record;
use app\models\ReportForm;
use Codeception\PHPUnit\ResultPrinter\Report;
use Yii;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionIndex()
    {
        $formatter = Yii::$app->formatter;

        $model = new ReportForm();

        $reportForm = $this->request->get('ReportForm');
        if (!$reportForm) {
            $currentMonth = $formatter->asDate('now', 'php:m');

            $model->monthActual = $currentMonth;
            $model->monthPlan = $currentMonth;
            $model->monthPrevious = $currentMonth;

            return $this->render('report', ['model' => $model]);
        }

        $model->load($this->request->get());

        $currentYear = Yii::$app->formatter->asDate('now', 'php:Y');

        $dateActual = $currentYear . '-' . $reportForm['monthActual'];
        $datePlanned = $currentYear . '-' . $reportForm['monthPlan'];
        $datePrevious = ($currentYear - 1) . '-' . $reportForm['monthPrevious'];

        $dateActual = $formatter->asDate($dateActual, 'php:Y-m');
        $datePlanned = $formatter->asDate($datePlanned, 'php:Y-m');
        $datePrevious = $formatter->asDate($datePrevious, 'php:Y-m');

        $actualYear = $formatter->asDate($dateActual, 'php:Y');
        $planYear = $formatter->asDate($datePlanned, 'php:Y');
        $previousYear = $formatter->asDate($datePrevious, 'php:Y');

        $selectedReport = $reportForm['reportId'];

        $accounts = collect(Data::getPLAccounts())
            ->where('visible', Account::VISIBLE_TRUE)
            ->sortBy('id')
            ->all();

        if ($selectedReport == ReportForm::REPORT_R7) {
            $actual = collect(Record::find()
                ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $dateActual])
                ->andWhere(['type' => RecordType::ACTUAL])
                ->all());

            $planned = collect(Record::find()
                ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $datePlanned])
                ->andWhere(['type' => RecordType::PLAN])
                ->all());

            return $this->render('report', [
                'model' => $model,
                'reportType' => ReportForm::REPORT_R7,
                'display_r7' => [
                    'actual' => $actual,
                    'planned' => $planned,
                    'actualYear' => $actualYear,
                    'planYear' => $planYear,
                    'accounts' => $accounts,
                ],
            ]);
        }

        if ($selectedReport == ReportForm::REPORT_R8) {
            $actual = collect(Record::find()
                ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $dateActual])
                ->andWhere(['type' => RecordType::ACTUAL])
                ->all());

            $previous = collect(Record::find()
                ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $datePrevious])
                ->andWhere(['type' => RecordType::ACTUAL])
                ->all());

            return $this->render('report', [
                'model' => $model,
                'reportType' => ReportForm::REPORT_R8,
                'display_r8' => [
                    'actual' => $actual,
                    'previous' => $previous,
                    'actualDate' => Yii::$app->formatter->asDate($dateActual, 'php:M-Y'),
                    'previousDate' => Yii::$app->formatter->asDate($datePrevious, 'php:M-Y'),
                    'accounts' => $accounts,
                ],
            ]);
        }

    }
}