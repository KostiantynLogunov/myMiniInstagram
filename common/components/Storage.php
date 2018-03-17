<?php

namespace common\components;

use yii\web\UploadedFile;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class Storage extends Component implements StorageInterface
{
    private $fileName;

    /**
     * Save given UploadFile instance to disk
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file);

        if ($path && $file->saveAs($path))
        {
            return $this->fileName;
        }
    }

    /**
     * Prepare path to save uploaded file
     * @param UploadedFile $file
     * return string|null
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $this->getFileName($file);
        // 0c/a9/454j5hbjk4b54kj5bh234j5bh4jk.jpg

        $path = $this->getStoragePath() . $this->fileName;
        //   /var/www/project/frontend/web/uploads/0c/a9/454j5hbjk4b54kj5bh234j5bh4jk.jpg

        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }

    }

    protected function getFileName(UploadedFile $file)
    {
        //$file->tempname -> /tmp/qio93kf

        $hash = sha1_file($file->tempName); //5itbhibhi35iu34h5i35iugb53ibg5
        $name = substr_replace($hash, '/', 2, 0);//5i/tbhibhi35iu34h5i35iugb53ibg5
        $name = substr_replace($name, '/', 5, 0);//5i/tb/hibhi35iu34h5i35iugb53ibg5
        return $name . '.' . $file->extension;//5i/tb/hibhi35iu34h5i35iugb53ibg5.jpg
    }

    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }


    public function getFile(string $filename)
    {
        return Yii::$app->params['storageUri'].$filename;
    }
}