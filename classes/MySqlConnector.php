<?php

class MySqlConnector {

    private $servername = cfg::mysql_server;
    private $username = cfg::mysql_user;
    private $password = cfg::mysql_password;
    private $database = cfg::mysql_database;
    

    public function connect() {

        $conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

}
