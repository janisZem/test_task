<?php

function __autoload($classname) {
    $filename = "./classes/" . $classname . ".php";
    include_once($filename);
}

$p = new ProcessDat(htmlspecialchars($_POST['path']));
$payments = $p->pharseFile();

/*
 * Find user selected data in file, save data from file
 */
foreach ($_POST['payments'] as $payment) {
    foreach ($payments as $filePayments) {
        if (htmlspecialchars($payment['date']) == $filePayments['date'] &&
                htmlspecialchars($payment['benefactor']) == $filePayments['benefactor'] &&
                htmlspecialchars($payment['amount']) == $filePayments['amount'] &&
                htmlspecialchars($payment['account_numer']) == $filePayments['account_numer']) {
            $p->savePayment($filePayments);
        }
    }
}



