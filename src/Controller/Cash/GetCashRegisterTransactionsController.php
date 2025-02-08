<?php

namespace App\Controller\Cash;

use App\Entity\Cash\CashRegister;
use App\Entity\Cash\Transaction;
use App\Repository\Cash\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCashRegisterTransactionsController extends AbstractController
{
    #[Route('/api/cash-registers/{id}/transactions', name: 'get_cash_register_transactions', methods: ['GET'])]
    public function __invoke(CashRegister $cashRegister, EntityManagerInterface $em): JsonResponse
    {
        $accounts = $cashRegister->getCashAccounts()->toArray();
        if (empty($accounts)) {
            return new JsonResponse(['transactions' => []], 200);
        }
        /** @var TransactionRepository $transactionRepository */
        $transactionRepository = $em->getRepository(Transaction::class);
        $transactions = $transactionRepository->getTransactions($accounts);

        return $this->json(['transactions' => $transactions], 200, [], ['groups' => ['transaction:read']]);
    }
}
