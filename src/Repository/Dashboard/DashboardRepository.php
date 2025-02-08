<?php

namespace App\Repository\Dashboard;

use App\Entity\Cash\CashRegister;
use App\Entity\Cash\Transaction;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class DashboardRepository
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array{totalCashRegisterBalance: float, totalTransactions: int, totalUsers: int}
     */
    public function getDashboardData(): array
    {
        $totalCashRegisterBalance = 0.0;
        $cashRegisters = $this->em->getRepository(CashRegister::class)->findAll();
        foreach ($cashRegisters as $register) {
            foreach ($register->getCashAccounts() as $account) {
                $totalCashRegisterBalance += $account->getBalance();
            }
        }
        $totalTransactions = (int) $this->em->createQueryBuilder()
            ->select('COUNT(t.id)')
            ->from(Transaction::class, 't')
            ->getQuery()
            ->getSingleScalarResult();
        $totalUsers = (int) $this->em->createQueryBuilder()
            ->select('COUNT(u.id)')
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'totalCashRegisterBalance' => $totalCashRegisterBalance,
            'totalTransactions' => $totalTransactions,
            'totalUsers' => $totalUsers,
        ];
    }
}
