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
use yii\helpers\Url;
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
        $datePlanned = $currentYear . '-' . $reportForm['month'];
        $datePrevious = ($currentYear - 1) . '-' . $reportForm['month'];

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
            case ReportForm::REPORT_R7:
                return $this->redirect(['r7', 'dateActual' => $dateActual, 'datePlanned' => $datePlanned]);
                break;
            case ReportForm::REPORT_R8:
                return $this->redirect(['r8', 'dateActual' => $dateActual, 'datePrevious' => $datePrevious]);
                break;
            default:
                Yii::$app->session->addFlash('messages', [
                    'message' => 'Please select appropriate report',
                    'class' => 'text-white bg-danger',
                ]);
                return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
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

    public function actionR7($dateActual, $datePlanned)
    {
        $formatter = Yii::$app->formatter;

        $dateActual = $formatter->asDate($dateActual, 'php:Y-m');
        $datePlanned = $formatter->asDate($datePlanned, 'php:Y-m');

        $accounts = collect(Data::getPLAccounts())
            ->where('visible', Account::VISIBLE_TRUE)
            ->sortBy('id')
            ->all();

        $actual = collect(Record::find()
            ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $dateActual])
            ->andWhere(['type' => RecordType::ACTUAL])
            ->all());

        $planned = collect(Record::find()
            ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $datePlanned])
            ->andWhere(['type' => RecordType::PLAN])
            ->all());

        if ($actual->count() == 0 || $planned->count() == 0) {
            Yii::$app->session->addFlash('messages', [
                'message' => 'Not enough data to display report',
                'class' => 'text-white bg-danger',
            ]);
            return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
        }

        $actualYear = $formatter->asDate($dateActual, 'php:Y');
        $planYear = $formatter->asDate($datePlanned, 'php:Y');

        return $this->render('display_r7', [
            'actual' => $actual,
            'planned' => $planned,
            'actualYear' => $actualYear,
            'planYear' => $planYear,
            'accounts' => $accounts,
        ]);

    }

    public function actionR8($dateActual, $datePrevious)
    {
        $formatter = Yii::$app->formatter;

        $dateActual = $formatter->asDate($dateActual, 'php:Y-m');
        $datePrevious = $formatter->asDate($datePrevious, 'php:Y-m');

        $accounts = collect(Data::getPLAccounts())
            ->where('visible', Account::VISIBLE_TRUE)
            ->sortBy('id')
            ->all();

        $actual = collect(Record::find()
            ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $dateActual])
            ->andWhere(['type' => RecordType::ACTUAL])
            ->all());

        $previous = collect(Record::find()
            ->where('DATE_FORMAT(date, \'%Y-%m\') = :date', [':date' => $datePrevious])
            ->andWhere(['type' => RecordType::ACTUAL])
            ->all());

        if ($actual->count() == 0 || $previous->count() == 0) {
            Yii::$app->session->addFlash('messages', [
                'message' => 'Not enough data to display report',
                'class' => 'text-white bg-danger',
            ]);
            return $this->redirect(Yii::$app->request->referrer ?? Yii::$app->homeUrl);
        }

        return $this->render('display_r8', [
            'actual' => $actual,
            'previous' => $previous,
            'actualDate' => Yii::$app->formatter->asDate($dateActual, 'php:M-Y'),
            'previousDate' => Yii::$app->formatter->asDate($datePrevious, 'php:M-Y'),
            'accounts' => $accounts,
        ]);
    }
}