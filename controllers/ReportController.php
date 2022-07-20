<?php


namespace app\controllers;

use app\enums\RecordType;
use app\exceptions\RecordsNotFoundForDateAndTypeException;
use app\helpers\Data;
use app\helpers\Tools;
use app\models\Account;
use app\models\Dataset;
use app\models\Record;
use app\models\ReportForm;
use app\models\ReportA2P;
use app\models\ReportPL_VR1;
use app\models\ReportYOY;
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
            $model->month = $formatter->asDate('now', 'php:m');

            return $this->render('report', ['model' => $model]);
        }

        $model->load($this->request->get());

        $selectedReport = $reportForm['reportId'];

        $currentYear = $formatter->asDate('now', 'php:Y');

        $dateActual = $currentYear . '-' . $reportForm['month'];

        switch ($selectedReport) {
            case ReportForm::REPORT_PT1:
                return $this->redirect(['a2p-pl-pt', 'date' => $dateActual]);
                break;
            case ReportForm::REPORT_PT2:
                return $this->redirect(['year-over-year-pl', 'date' => $dateActual]);
                break;
            case ReportForm::REPORT_PL_VR1:
                return $this->redirect(['pl-vr1', 'date' => $dateActual]);
                break;
        }

        $datePlanned = $currentYear . '-' . $reportForm['month'];
        $datePrevious = ($currentYear - 1) . '-' . $reportForm['month'];

        $dateActual = $formatter->asDate($dateActual, 'php:Y-m');
        $datePlanned = $formatter->asDate($datePlanned, 'php:Y-m');
        $datePrevious = $formatter->asDate($datePrevious, 'php:Y-m');

        $actualYear = $formatter->asDate($dateActual, 'php:Y');
        $planYear = $formatter->asDate($datePlanned, 'php:Y');
        $previousYear = $formatter->asDate($datePrevious, 'php:Y');

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

    public function actionA2pPlPt($date)
    {
        try {
            $reportModel = new ReportA2P($date);
            return $this->render('report_a2p', ['model' => $reportModel]);
        } catch (RecordsNotFoundForDateAndTypeException $e) {
            Yii::$app->session->addFlash('messages', [
                'message' => $e->getMessage(),
                'class' => 'text-white bg-danger',
            ]);
            return $this->redirect(['report/index']);
        }

    }

    public function actionYearOverYearPl($date)
    {
        try {
            $reportModel = new ReportYOY($date);
            return $this->render('report_yoy', ['model' => $reportModel]);
        } catch (RecordsNotFoundForDateAndTypeException $e) {
            Yii::$app->session->addFlash('messages', [
                'message' => $e->getMessage(),
                'class' => 'text-white bg-danger',
            ]);
            return $this->redirect(['report/index']);
        }
    }

    public function actionPlVr1($date)
    {
        try {
            $reportModel = new ReportPL_VR1($date);
            $this->view->registerJsFile('@web/js/pl_vr1.js', ['depends' => [\yii\web\JqueryAsset::class], [\yii\web\View::POS_END]]);
            return $this->render('report_pl_vr1', ['model' => $reportModel]);
        } catch (RecordsNotFoundForDateAndTypeException $e) {
            Yii::$app->session->addFlash('messages', [
                'message' => $e->getMessage(),
                'class' => 'text-white bg-danger',
            ]);
            return $this->redirect(['report/index']);
        }
    }
}