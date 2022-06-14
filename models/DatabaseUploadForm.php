<?php


namespace app\models;


use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class DatabaseUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $excelFile;
    public $column;
    public $overwrite;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx, csv'],
            [['column'], 'required'],
            [['overwrite'], 'boolean'],
            [['column'], 'string'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->excelFile->saveAs(Yii::getAlias('@webroot/uploads') . DIRECTORY_SEPARATOR . $this->excelFile->baseName . '.' . $this->excelFile->extension);
            return true;
        } else {
            return false;
        }
    }
}