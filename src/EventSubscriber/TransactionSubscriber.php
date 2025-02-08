<?php

namespace App\EventSubscriber;

use App\Entity\Cash\Transaction;
use App\Service\TransactionService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TransactionSubscriber implements EventSubscriber
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param LifecycleEventArgs<EntityManagerInterface> $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Transaction) {
            return;
        }
        $om = $args->getObjectManager();
        $this->transactionService->processTransaction($entity);
        if ($om instanceof EntityManagerInterface) {
            $om->flush();
        }
    }

    /**
     * @param LifecycleEventArgs<EntityManagerInterface> $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Transaction) {
            return;
        }
        $om = $args->getObjectManager();
        $this->transactionService->processTransaction($entity);
        if ($om instanceof EntityManagerInterface) {
            $om->flush();
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::preUpdate,
        ];
    }
}
