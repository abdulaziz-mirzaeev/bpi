<?php

namespace app\controllers;

use app\helpers\Data;
use app\helpers\Tools;
use app\models\Account;
use app\models\DatabaseUploadForm;
use app\models\Record;
use moonland\phpexcel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUpload()
    {
        $model = new DatabaseUploadForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');

            if ($model->upload()) {
                $file = Yii::getAlias('@webroot/uploads/') . $model->excelFile->name;

                $reader = IOFactory::createReader(IOFactory::identify($file));
                $spreadsheet = $reader->load($file);
                $data = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);

                if ($data[7]['B'] == 'GROSS SALES' && $data[43]['B'] == 'NET INCOME') {
                    $p_l_accounts = array_slice($data, 6, 37);

                    $accounts = ArrayHelper::getColumn($p_l_accounts, 'B');
                    $columnData = ArrayHelper::getColumn($p_l_accounts, strtoupper($model->column));

                    $month = trim($data[4][$model->column]);
                    $year = explode(' ', trim($data[5][$model->column]))[0];
                    $type = explode(' ', trim($data[5][$model->column]))[1];

                    $date = date('Y-m-d', strtotime($month . ' ' . $year));

                    if (!$model->overwrite && Record::find()->where(['type' => $type, 'date' => $date])->count() > 1) {
                        Yii::$app->session->setFlash('alerts', 'Data for the given period already exits');
                        return $this->render('upload', ['model' => $model]);
                    }

                    if ($month != 'YTD') {

                        if ($model->overwrite && Record::find()->where(['type' => $type, 'date' => $date])->count() > 1) {
                            Record::deleteAll(['type' => $type, 'date' => $date]);
                        }

                        $transaction = Record::getDb()->beginTransaction();

                        try {
                            foreach ($columnData as $id => $value) {
                                $record = new Record();
                                $record->date = $date;
                                $record->value = $value;
                                $record->account_id = $id + 1;
                                $record->type = $type;
                                $record->save();
                            }
                            $transaction->commit();

                            Yii::$app->session->setFlash('alerts', 'Successfully imported for: ' . $date . ' ' . $type);
                            return $this->goHome();
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('alerts', 'Something went wrong!');
                            throw $e;
                        }

                    }
                } else {
                    Yii::$app->session->setFlash('alerts', 'Database file is invalid');
                    return $this->render('upload', ['model' => $model]);
                }
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function actionFiles()
    {
        $start = microtime(true);
        $files = FileHelper::findFiles(Yii::getAlias('@webroot/uploads'), ['only' => ['*.xls', '*.xlsx']]);

        $reader = IOFactory::createReader(IOFactory::identify($files[0]));
        $spreadsheet = $reader->load($files[0]);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        $end = microtime(true);
        echo 'Execution time: ' . ($end - $start);
        exit;
    }

    public function actionData()
    {
        $data = ArrayHelper::index(Record::find()->asArray()->all(), null, ['date', 'type']);
        $accounts = Data::getAccounts();

        return $this->render('data', ['data' => $data, 'accounts' => $accounts]);

    }
}