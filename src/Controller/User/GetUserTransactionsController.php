<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Repository\Cash\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetUserTransactionsController extends AbstractController
{
    public function __construct(public TransactionRepository $transactionRepository)
    {
    }

    #[Route('/api/user/transactions', name: 'get_user_transactions', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated.'], 401);
        }
        $accounts = $user->getUserAccounts()->toArray();
        if (empty($accounts)) {
            return new JsonResponse(['transactions' => []], 200);
        }

        $transactions = $this->transactionRepository->getTransactions($accounts);

        return $this->json(['transactions' => $transactions], 200, [], ['groups' => ['transaction:read']]);
    }
}
