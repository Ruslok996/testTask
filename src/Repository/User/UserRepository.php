<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return array{totalBalance: float, transactionCount: int}
     */
    public function getUserDashboardData(User $user): array
    {
        $userAccounts = $user->getUserAccounts()->toArray();
        $totalBalance = 0.0;
        foreach ($userAccounts as $account) {
            $totalBalance += $account->getBalance();
        }
        $qb = $this->createQueryBuilder('u')
            ->select('COUNT(t.id)')
            ->join('u.userAccounts', 'a')
            ->join('App\Entity\Cash\Transaction', 't', 'WITH', 't.sourceAccount = a OR t.destinationAccount = a')
            ->where('u = :user')
            ->setParameter('user', $user);
        $transactionCount = (int) $qb->getQuery()->getSingleScalarResult();

        return [
            'totalBalance' => $totalBalance,
            'transactionCount' => $transactionCount,
        ];
    }
}
