<?php

class Connection
{
    public mysqli $conn;

    public function __construct()
    {
        $servername = getenv('MYSQL_HOSTNAME');
        $username = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');
        $db_name = getenv('MYSQL_DATABASE');

        $max_retries = 10;
        $retry_count = 0;

        while ($retry_count < $max_retries) {
            try {
                $this->conn = new mysqli($servername, $username, $password, $db_name);
                if (!$this->conn->connect_error) {
                    $this->conn->set_charset('utf8');
                    return;
                }
            } catch (mysqli_sql_exception $e) {
                if ($retry_count === $max_retries - 1) {
                    die("Connection failed after $max_retries retries: " . $e->getMessage());
                }
            }

            $retry_count++;
            sleep(2); // Wait for 2 seconds before retrying
        }

        die("Connection failed after $max_retries retries: unable to connect to database.");
    }
}
