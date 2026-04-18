<?php

require_once "model.php";

class dashboard extends modelAdmin
{
    public function getMonthlyEarnings(): int
    {
        $query = "SELECT SUM(total_cost) as total FROM bill WHERE MONTH(timestamp) = MONTH(CURRENT_DATE()) AND YEAR(timestamp) = YEAR(CURRENT_DATE()) AND status != 0";
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function getAnnualEarnings(): int
    {
        $query = "SELECT SUM(total_cost) as total FROM bill WHERE YEAR(timestamp) = YEAR(CURRENT_DATE()) AND status != 0";
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
    }

    public function getPendingRequests(): int
    {
        $query = "SELECT COUNT(*) as count FROM bill WHERE status = 0";
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['count'] ?? 0;
    }

    public function getTasksPercentage(): int
    {
        $totalQuery = "SELECT COUNT(*) as count FROM bill";
        $total = $this->conn->query($totalQuery)->fetch_assoc()['count'] ?? 0;

        if ($total == 0) {
            return 0;
        }

        $completedQuery = "SELECT COUNT(*) as count FROM bill WHERE status != 0";
        $completed = $this->conn->query($completedQuery)->fetch_assoc()['count'] ?? 0;

        return (int)(($completed / $total) * 100);
    }
}
