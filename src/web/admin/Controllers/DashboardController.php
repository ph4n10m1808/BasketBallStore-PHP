<?php

require_once BASE_PATH . "/admin/Models/dashboard.php";

class DashboardController
{
    public dashboard $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new dashboard();
    }

    public function getData(): void
    {
        $stats = $this->dashboardModel->getStats();
        
        $monthlyEarnings = number_format($stats['monthly_earnings']);
        $annualEarnings = number_format($stats['annual_earnings']);
        $pendingRequests = $stats['pending_requests'];
        
        $total = $stats['total_bills'];
        $completed = $stats['completed_bills'];
        $tasksPercentage = ($total > 0) ? (int)(($completed / $total) * 100) : 0;

        require_once BASE_PATH . "/admin/Views/index.php";
    }
}
