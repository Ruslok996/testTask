<?php

namespace App\Controller\Cash;

use App\Entity\Cash\Transaction;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CancelTransactionController extends AbstractController
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    #[Route('/api/transactions/{id}/cancel', name: 'cancel_transaction', methods: ['POST'])]
    public function __invoke(Transaction $transaction, EntityManagerInterface $em): JsonResponse
    {
        if ($transaction->isCanceled()) {
            return new JsonResponse(['message' => 'Transaction already canceled.'], 400);
        }
        $this->transactionService->reverseTransaction($transaction);
        $transaction->setCanceled(true);
        $em->flush();

        return new JsonResponse(['message' => 'Transaction canceled successfully.'], 200);
    }
}
