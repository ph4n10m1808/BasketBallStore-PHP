<?php

class Connect
{
    public mysqli $conn;

    public function __construct()
    {
        $config = require BASE_PATH . '/config/database.php';
        
        $servername = $config['host'];
        $username   = $config['username'];
        $password   = $config['password'];
        $db_name    = $config['database'];
        
        $max_retries = $config['options']['retries'];
        $retry_count = 0;
        $host_prefix = $config['options']['persistent'] ? 'p:' : '';

        while ($retry_count < $max_retries) {
            try {
                $this->conn = new mysqli($host_prefix . $servername, $username, $password, $db_name);
                if (!$this->conn->connect_error) {
                    $this->conn->set_charset($config['charset']);
                    return;
                }
            } catch (mysqli_sql_exception $e) {
                if ($retry_count === $max_retries - 1) {
                    die("Connection failed after $max_retries retries: " . $e->getMessage());
                }
            }

            $retry_count++;
            sleep($config['options']['retry_wait']);
        }

        die("Connection failed after $max_retries retries: unable to connect to database.");
    }
}
