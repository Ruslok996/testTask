<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserDashboardController extends AbstractController
{
    public function __construct(public UserRepository $userDashboardRepository)
    {
    }

    #[Route('/api/user/dashboard', name: 'get_user_dashboard', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated.'], 401);
        }
        $data = $this->userDashboardRepository->getUserDashboardData($user);

        return new JsonResponse($data, 200);
    }
}
