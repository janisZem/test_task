<?php

function __autoload($classname) {
    $filename = "./classes/" . $classname . ".php";
    include_once($filename);
}

$a = new FileUpload();
$a->tarDir = cfg::upload_dir;
$a->file = 'payment_file';
if (strtoupper($a->getFileType()) == strtoupper(htmlspecialchars($_POST["file_type"]))) {
    $a->doUpload();
} else {
    include_once 'header.php';
    echo '<div class="alert alert-danger" role="alert">Wrong file format!</div>';
    include_once 'footer.php';
    return;
}

$b = new FileProcess($a->tarDir . $a->getFileName());
$b->process($a->getFileType());




