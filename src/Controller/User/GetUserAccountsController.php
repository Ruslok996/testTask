<?php

namespace App\Controller\User;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserAccountsController extends AbstractController
{
    #[Route('/api/user/accounts', name: 'get_user_accounts', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated.'], 401);
        }
        $accounts = $user->getUserAccounts()->toArray();

        return $this->json(['accounts' => $accounts], 200, [], ['groups' => ['userAccount:read']]);
    }
}
