<?php

class FileReader {

    public $fileName = "";

    public function __construct($path) {
        $this->fileName = $path;
    }

    public function open($mode) {
        return fopen($this->fileName, $mode);
    }

    public function close($file) {
        fclose($file);
    }

}
