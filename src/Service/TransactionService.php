<?php

namespace App\Service;

use App\Entity\Cash\Transaction;
use App\Enum\TransactionEnum;

class TransactionService
{
    public function processTransaction(Transaction $transaction): void
    {
        if ($transaction->isCanceled()) {
            return;
        }
        switch ($transaction->getType()) {
            case TransactionEnum::DEPOSIT:
                $destination = $transaction->getDestinationAccount();
                $destination->setBalance($destination->getBalance() + $transaction->getAmount());
                break;
            case TransactionEnum::TRANSFER:
                $source = $transaction->getSourceAccount();
                $destination = $transaction->getDestinationAccount();
                $source->setBalance($source->getBalance() - $transaction->getAmount());
                $destination->setBalance($destination->getBalance() + $transaction->getAmount());
                break;
        }
    }

    public function reverseTransaction(Transaction $transaction): void
    {
        if ($transaction->isCanceled()) {
            return;
        }
        switch ($transaction->getType()) {
            case TransactionEnum::DEPOSIT:
                $destination = $transaction->getDestinationAccount();
                $destination->setBalance($destination->getBalance() - $transaction->getAmount());
                break;
            case TransactionEnum::TRANSFER:
                $source = $transaction->getSourceAccount();
                $destination = $transaction->getDestinationAccount();
                $source->setBalance($source->getBalance() + $transaction->getAmount());
                $destination->setBalance($destination->getBalance() - $transaction->getAmount());
                break;
        }
    }
}
