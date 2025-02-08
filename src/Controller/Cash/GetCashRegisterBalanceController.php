<?php

namespace App\Controller\Cash;

use App\Entity\Cash\CashRegister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCashRegisterBalanceController extends AbstractController
{
    #[Route('/api/cash-registers/{id}/balance', name: 'get_cash_register_balance', methods: ['GET'])]
    public function __invoke(CashRegister $cashRegister, EntityManagerInterface $em): JsonResponse
    {
        $balance = 0.0;
        foreach ($cashRegister->getCashAccounts() as $account) {
            $balance += $account->getBalance();
        }

        return new JsonResponse(['balance' => $balance], 200);
    }
}
