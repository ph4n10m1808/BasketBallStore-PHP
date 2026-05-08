<?php

require_once("connect.php");

class modelAdmin
{
    public mysqli $conn;
    public function __construct()
    {
        $conn_obj = new Connect();
        $this->conn = $conn_obj->conn;
    }

    public function resultReturnArray($query): array
    {
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
