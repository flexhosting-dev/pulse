<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        // Placeholder statistics - will be replaced with actual data from services
        $stats = [
            'totalStaff' => 53,
            'maleStaff' => 41,
            'femaleStaff' => 12,
            'pendingApprovals' => 7,
            'activePIPs' => 2,
            'completedReviews' => 45,
        ];

        $departments = [
            ['name' => 'Conservation', 'count' => 18, 'color' => 'primary'],
            ['name' => 'Finance', 'count' => 8, 'color' => 'success'],
            ['name' => 'Administration', 'count' => 12, 'color' => 'info'],
            ['name' => 'Programs', 'count' => 15, 'color' => 'warning'],
        ];

        $recentActivities = [
            [
                'type' => 'approval',
                'icon' => 'bi-check-circle',
                'color' => 'success',
                'message' => 'John Doe\'s KPIs approved by supervisor',
                'time' => '2 hours ago',
            ],
            [
                'type' => 'submission',
                'icon' => 'bi-send',
                'color' => 'primary',
                'message' => 'Jane Smith submitted mid-year review',
                'time' => '4 hours ago',
            ],
            [
                'type' => 'pip',
                'icon' => 'bi-graph-up',
                'color' => 'warning',
                'message' => 'PIP created for Mark Johnson',
                'time' => 'Yesterday',
            ],
            [
                'type' => 'review',
                'icon' => 'bi-clipboard-check',
                'color' => 'info',
                'message' => 'Annual review cycle initiated',
                'time' => '2 days ago',
            ],
        ];

        $pendingApprovals = [
            [
                'employee' => 'Alice Brown',
                'type' => 'KPI',
                'status' => 'Pending Supervisor',
                'statusClass' => 'pending-sup',
                'submitted' => '2026-01-14',
            ],
            [
                'employee' => 'Bob Wilson',
                'type' => 'Mid-Year',
                'status' => 'Pending HR',
                'statusClass' => 'pending-hr',
                'submitted' => '2026-01-13',
            ],
            [
                'employee' => 'Carol Davis',
                'type' => 'PIP',
                'status' => 'Pending Supervisor',
                'statusClass' => 'pending-sup',
                'submitted' => '2026-01-12',
            ],
        ];

        return $this->render('dashboard/index.html.twig', [
            'stats' => $stats,
            'departments' => $departments,
            'recentActivities' => $recentActivities,
            'pendingApprovals' => $pendingApprovals,
        ]);
    }
}
