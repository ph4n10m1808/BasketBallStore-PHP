<?php

require_once "model.php";

class dashboard extends modelAdmin
{
    public function getStats(): array
    {
        $query = "SELECT 
                    SUM(CASE WHEN MONTH(timestamp) = MONTH(CURRENT_DATE()) AND YEAR(timestamp) = YEAR(CURRENT_DATE()) AND status != 0 THEN total_cost ELSE 0 END) as monthly_earnings,
                    SUM(CASE WHEN YEAR(timestamp) = YEAR(CURRENT_DATE()) AND status != 0 THEN total_cost ELSE 0 END) as annual_earnings,
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_requests,
                    COUNT(*) as total_bills,
                    SUM(CASE WHEN status != 0 THEN 1 ELSE 0 END) as completed_bills
                  FROM bill";
        return $this->conn->query($query)->fetch_assoc() ?: [
            'monthly_earnings' => 0,
            'annual_earnings' => 0,
            'pending_requests' => 0,
            'total_bills' => 0,
            'completed_bills' => 0
        ];
    }
}
