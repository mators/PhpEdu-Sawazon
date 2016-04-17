<?php

namespace models;


class Picture implements Model
{
    private static $FILE_KEYS = ['name', 'type', 'size', 'tmp_name', 'error'];
    private static $ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    /**
     * @var string
     */
    private $pictureString;

    /**
     * @var int
     */
    private $pictureId;

    private $errors = [];

    /**
     * @var array|null
     */
    private $file = null;

    private $resizeTo = [];

    /**
     * Image constructor.
     * @param string $pictureString
     * @param int $id
     */
    public function __construct($pictureString = null, $id = null)
    {
        $this->pictureString = $pictureString;
        $this->pictureId = $id;
    }

    /**
     * @return string
     */
    public function getPictureString()
    {
        return $this->pictureString;
    }

    /**
     * @param string $pictureString
     */
    public function setImageString($pictureString)
    {
        $this->pictureString = $pictureString;
    }

    /**
     * @param array|null $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param array $resizeTo
     */
    public function setResizeTo($resizeTo)
    {
        $this->resizeTo = $resizeTo;
    }

    public function validate()
    {
        $this->errors = [];

        if (null == $this->file) {
            $file = elements(self::$FILE_KEYS, $_FILES["file"]);
        } else {
            $file = $this->file;
        }

        if ($file["error"] > 0) {
            $this->pictureString = "";
            $this->errors["error"] = "Error";
        } else {
            if (!in_array(strtolower($file["type"]), self::$ALLOWED_FILE_TYPES)) {
                $this->errors["file"] = "File format not supported.";
            } else {

                if ($file["size"] > 512000) {
                    $this->errors["file"] = "Photo can be up to 500kB.";
                }

                list($width, $height) = getimagesize($file['tmp_name']);

                if ($width < 128 || $height < 128) {
                    $this->errors["file"] = "Photo must be at least 128x128.";
                }
            }

            if (empty($this->errors)) {
                $this->pictureString = $this->pngStringFromImageInfo($file);
            }
        }

        return empty($this->errors);
    }


    private function pngStringFromImageInfo($file)
    {
        if (empty($file["tmp_name"])) {
            return "";
        }
        $dim = getimagesize($file['tmp_name']);

        switch(strtolower($dim['mime'])) {
            case 'image/png':
                $image = imagecreatefrompng($file['tmp_name']);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file['tmp_name']);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file['tmp_name']);
                break;
            default: die();
        }

        if (!empty($this->resizeTo)) {
            $imageDest = imagecreatetruecolor($this->resizeTo[0], $this->resizeTo[1]);
            imagecopyresized($imageDest, $image, 0, 0, 0, 0, $this->resizeTo[0], $this->resizeTo[1], $dim[0], $dim[1]);
            $image = $imageDest;
        }

        ob_start();
        imagepng($image);
        $contents =  ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    public static function getResized($image, $newW, $newH)
    {
        $imageDest = imagecreatetruecolor($newW, $newH);
        imagecopyresized($imageDest, $image, 0, 0, 0, 0, $newW, $newH, imagesx($image), imagesy($image));
        return $imageDest;
    }

    public function getPictureId()
    {
        return $this->pictureId;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
