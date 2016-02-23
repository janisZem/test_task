<?php

class ProcessDat {

    public $path = '';
    public $con;

    public function __construct($path) {
        $this->path = $path;
        $d = new MySqlConnector();
        $this->con = $mysqli = $d->connect();
    }

    public function processDat() {
        //$this->saveFile($this->path);
        $this->printRecords($this->pharseFile());
    }

    /* private function saveFile($name) {
      $n = htmlspecialchars($name);
      $result = $this->con->query("INSERT INTO files (name, status, upload_date) VALUES ('$n', '00', now())");
      return $this->con->insert_id;
      } */

    /*
     * wrong date and amount with dot not comma
     */

    private function getDBPayments($data) {
        //($data['date']);
        $date = explode('/', $data['date']);

        $d = $date[2] . '-' . $date[1] . '-' . $date[0];
        $data['date'] = date($d . ' 00:00:00');

        $sql = '  SELECT * '
                . ' FROM payments '
                . 'WHERE payment_date = ? '
                . '  AND amount = ? '
                . '  AND account_number = ? '
                . '  AND benefactor = ? ';


        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param('sdss', $data['date'], str_replace(',', '.', $data['amount']), $data['account_numer'], $data['benefactor']);
            $stmt->execute();
            //print_r($stmt->get_result()->fetch_assoc());
            return $stmt->get_result()->fetch_assoc();
        } else {
            var_dump($this->con->error);
        }
    }

    public function savePayment($payment) {
        $date = explode('/', $payment['date']);

        $d = $date[2] . '-' . $date[1] . '-' . $date[0];
        $a = date($d . ' 00:00:00');
        $amount = str_replace(',', '.', $payment['amount']);
        $account = $payment['account_numer'];
        $purpose = $payment['payment_purpose'];
        $bene = $payment['benefactor'];
        $sql = "  INSERT INTO payments ("
                . "           payment_date"
                . "         , system_date"
                . "         , amount"
                . "         , account_number"
                . "         , payment_purpose"
                . "         , benefactor) VALUES (?, now(), ?, ?, ?, ?) ";


        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param('sdsss', $a, $amount, $account, $purpose, $bene);
            $stmt->execute();
            return;
        } else {
            var_dump($this->con->error);
        }
    }

    public function pharseFile() {
        $paymentArray = [];
        $file = new FileReader($this->path);
        $fileStream = $file->open('r');

        while (!feof($fileStream)) {
            $line = explode("|", utf8_encode(fgets($fileStream)));
            if (count($line) <= 9 || $line[3] < 0) { //check for all values and only positive amounts
                continue;
            }

            $tmp_array = [
                'date' => $line[1],
                'amount' => $line[3],
                'account_numer' => trim(explode(';', explode(':', $line[5])[1])[0]),
                'payment_purpose' => explode(':', explode(';', $line[5])[3])[1],
                'benefactor' => explode(' ', explode(':', explode(';', $line[5])[2])[1])[1] . ' ' . explode(' ', explode(':', explode(';', $line[5])[2])[1])[2]
            ];

            array_push($paymentArray, $tmp_array);
        }
        $file->close($fileStream);
        return $paymentArray;
    }

    /*
     * This function can be used for an other file formats
     */

    private function printRecords($payments) {
        include_once 'header.php';
        ?>
        <div class="container">
            <h2>Compare payments</h2>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="5" style="width: 50%">EXISTS</th>
                        <th colspan="5" style="width: 50%">UPLOADED</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    foreach ($payments as $payment) {
                        $dbPayment = $this->getDBPayments($payment);
                        if (count($dbPayment) > 0) {
                            ?>
                            <tr>
                                <td><?php echo $dbPayment['payment_date']; ?></td>
                                <td><?php echo $dbPayment['benefactor']; ?></td>
                                <td><?php echo $dbPayment['amount']; ?></td>
                                <td colspan="2"><?php echo $dbPayment['account_number']; ?></td>                                         
                                <td><?php echo $payment['date']; ?></td>
                                <td><?php echo $payment['benefactor']; ?></td>
                                <td><?php echo $payment['amount']; ?></td>
                                <td colspan="2"><?php echo $payment['account_numer']; ?></td>
                            </tr>
                            <?php
                        } else {
                            ?>                
                            <tr class="payment-row">
                                <td colspan="5" align="center">NOT EXISTS</td>                    
                                <td class="info p-date"><?php echo $payment['date']; ?></td>
                                <td class="info p-benefactor"><?php echo $payment['benefactor']; ?></td>
                                <td class="info p-amount"><?php echo $payment['amount']; ?></td>
                                <td class="info p-account_numer"><?php echo $payment['account_numer']; ?></td>
                                <td class="info">
                                    <div class="checkbox">
                                        <label>
                                            <input class="payment-checkbox" type="checkbox">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="10" align="right">
                            <div style="display: inline;" class="checkbox">
                                <label>
                                    <input onclick="PAGE.PROCESS.checkAll()" type="checkbox">Check/Uncheck all
                                </label>
                            </div>
                            <input id="file_path" type="hidden" value="<?php echo $this->path; ?>">
                            <button type="submit" onclick="PAGE.PROCESS.store()" class="btn btn-default">Submit</button>
                        </td> 
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
        include_once 'footer.php';
    }

}
