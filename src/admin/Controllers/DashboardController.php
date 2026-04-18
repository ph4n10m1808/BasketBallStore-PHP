<?php

require_once "./Models/dashboard.php";

class DashboardController
{
    public dashboard $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new dashboard();
    }

    public function getData(): void
    {
        $monthlyEarnings = number_format($this->dashboardModel->getMonthlyEarnings());
        $annualEarnings = number_format($this->dashboardModel->getAnnualEarnings());
        $pendingRequests = $this->dashboardModel->getPendingRequests();
        $tasksPercentage = $this->dashboardModel->getTasksPercentage();

        require_once "view/index.php";
    }
}
