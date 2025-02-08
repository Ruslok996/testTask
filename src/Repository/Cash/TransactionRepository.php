<?php

namespace App\Repository\Cash;

use App\Entity\Cash\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @param array<int, object> $accounts
     *
     * @return Transaction[]
     */
    public function getTransactions(array $accounts): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.sourceAccount IN (:accounts) OR t.destinationAccount IN (:accounts)')
            ->setParameter('accounts', $accounts);
        /** @var Transaction[] $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
