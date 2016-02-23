<?php

class FileProcess {

    public $fileType = '';
    public $filePath = '';
    public $error = false;

    public function __construct($path) {
        $this->filePath = $path;
    }

    public function process($type) {
        switch ($type) {
            case 'dat':
                $p = new ProcessDat($this->filePath);
                $p->processDat();
                break;
            case 'xml':
                //write me
                break;
            default : $this->error = true;
        }
    }



}
