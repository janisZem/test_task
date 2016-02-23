<?php

class FileUpload {

    public $tarDir = "";
    public $file = "";

    public function getFileType() {
        return pathinfo($_FILES[$this->file]['name'], PATHINFO_EXTENSION);
    }

    public function getFileName() {
        return $_FILES[$this->file]["name"];
    }

    public function doUpload() {
        $target_file = $this->tarDir . basename($_FILES[$this->file]["name"]);
        if (move_uploaded_file($_FILES[$this->file]['tmp_name'], $target_file)) {
            return true;
        }
    }

}
