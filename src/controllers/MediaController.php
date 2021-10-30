<?php

namespace grintea\controllers;

use grintea\exceptions\ValidationException;
use Ubiquity\attributes\items\acl\Allow;
use grintea\controllers\ControllerBase;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\security\acl\controllers\AclControllerTrait;

#[Route('media',automated:true,inherited:true)]
class MediaController extends ControllerBase {
    use AclControllerTrait;

    #[Allow('@admin')]
    public function index(){}

    public function upload() {
        $target_dir = \ROOT . \DS;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $errors["image_type"] = "File is not an image.";
                throw new ValidationException( $errors );
                $uploadOk = 0;
            }
        }

    }

}