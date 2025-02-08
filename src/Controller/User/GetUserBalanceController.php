<?php

namespace App\Controller\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserBalanceController extends AbstractController
{
    #[Route('/api/user/balance', name: 'get_user_balance', methods: ['GET'])]
    public function __invoke(EntityManagerInterface $em): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated.'], 401);
        }
        $balance = 0.0;
        foreach ($user->getUserAccounts() as $account) {
            $balance += $account->getBalance();
        }

        return new JsonResponse(['balance' => $balance], 200);
    }
}
