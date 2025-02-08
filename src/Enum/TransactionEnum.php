<?php

namespace App\Enum;

enum TransactionEnum: string
{
    case DEPOSIT = 'deposit';
    case TRANSFER = 'transfer';
}
