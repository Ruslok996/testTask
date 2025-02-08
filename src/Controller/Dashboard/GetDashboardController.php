<?php

namespace App\Controller\Dashboard;

use App\Repository\Dashboard\DashboardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetDashboardController extends AbstractController
{
    public function __construct(public DashboardRepository $dashboardRepository)
    {
    }

    #[Route('/api/dashboard', name: 'get_dashboard', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $data = $this->dashboardRepository->getDashboardData();

        return new JsonResponse($data, 200);
    }
}
