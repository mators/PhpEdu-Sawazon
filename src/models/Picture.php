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

    private $errors = [];

    /**
     * Image constructor.
     * @param string $pictureString
     */
    public function __construct($pictureString = null)
    {
        $this->pictureString = $pictureString;
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

    public function validate()
    {
        $this->errors = [];

        $file = elements(self::$FILE_KEYS, $_FILES["file"]);

        if ($file["error"] > 0) {
            $this->pictureString = "";
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
                $this->pictureString = stringFromImageInfo($file);
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
