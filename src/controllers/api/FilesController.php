<?php

namespace bb\controllers\api;

use Bb;
use bb\base\ApiController;
use yii\web\UploadedFile;
use bb\models\api\FileModel;

class FilesController extends ApiController
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actionUpload()
    {
        if (Bb::$app->request->isPost) {

            $model = new FileModel();
            $model->imageFile = UploadedFile::getInstanceByName('fileUpload');


            $status = $model->upload();

            if ($status['success'] == true) {
                return [ "success" => true, "assetId" => $status['assetId'], "elementId" => $status['elementId'] ];
            } else {
                return [ "success" => false, "message" => $status["message"]];
            } // if model upload

        } else {
            return [ "success" => false, "message" => "No post"];
        } // if bb app request

    } // function

} // class
