<?php

namespace bb\models\api;

use Bb;
use bb\helpers\HyiiHelper;
use bb\models\PostElementsModel;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use bb\models\AssetModel;

class FileModel extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    private $publicAssetPath;

    private $privateAssetPath;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->publicAssetPath = Bb::getAlias("@public_asset_folder_path");
        $this->privateAssetPath = Bb::getAlias("@secure_files_folder");
    }

    public function rules():array
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }


    public function upload():array
    {
        if ($this->validate()) {

            $asset = new AssetModel();
            $post = HyiiHelper::getPost();

            /**
             * Build the filename and path
             */
            $filename = $this->imageFile->baseName . '_temp.' . $this->imageFile->extension;
            $extension = $this->imageFile->extension;
            $fullFilePath = $this->privateAssetPath . DIRECTORY_SEPARATOR . $filename;

            /**
             * Save the file that was uploaded
             */
            $this->imageFile->saveAs($fullFilePath);

            /**
             * Prep the file extension
             */
            if (($extension == ".jpeg") || ($extension == ".JPEG") || ($extension == ".JPG")) {
                $extension = "jpg";
            }
            switch ($extension) {
                case ".jpg" : $extension = "jpg"; break;
                case ".png" : $extension = "png"; break;
            }
            $asset->type = $extension;

            /**
             * Get the post id
             */
            if (isset($post['postId'])) {
                $asset->post = $post['postId'];
            }

            /**
             * Save the asset and get the id
             */
            $asset->save();
            $assetId = $asset->id;

            /**
             * Resize and save the image as a thumbnail. Delete the other file.
             */
            $image = Image::getImagine();
            $filenameFinal = $assetId . "." . $extension;
            $fullFilePathFinal = $this->privateAssetPath . DIRECTORY_SEPARATOR . $filenameFinal;
            $image
                ->open($fullFilePath)
                ->thumbnail(new Box(1200, 1200))
                ->save($fullFilePathFinal, ['quality' => 70]);
            unlink($fullFilePath);

            /**
             * Make a new post element with this file
             */
            $newE = new PostElementsModel();

            if (isset($post['postId'])) {
                $newE->post = $post['postId'];
            } else {
                return [ "success" => false, "message" => "Could not create a new element for the picture because a post id was not given"];
            }

            if (isset($post['order'])) {
                $newE->order = $post['order'];
            } else {
                return [ "success" => false, "message" => "Could not create a new element for the picture because a element order was not given"];
            }

            $newE->data = $assetId;
            $newE->type = "picture";
            $newE->save();
            $elementId = $newE->id;


            return [ "success" => true, "message" => "", "assetId" => $assetId, "elementId" => $elementId];

        } else {
            return [ "success" => false, "message" => "Failure to validate"];
        }

    } // function

} // class