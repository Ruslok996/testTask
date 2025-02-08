<?php

namespace App\Controller\Cash;

use App\Entity\Account\CashAccount;
use App\Entity\Account\UserAccount;
use App\Entity\Cash\Transaction;
use App\Entity\User\User;
use App\Enum\TransactionEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransferFundsController extends AbstractController
{
    #[Route('/api/transfer', name: 'transfer_funds', methods: ['POST'])]
    public function __invoke(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'User not authenticated.'], 401);
        }
        $data = json_decode($request->getContent(), true);
        if (
            !is_array($data)
            || !isset($data['sourceAccountId'], $data['destinationAccountId'], $data['amount'])
        ) {
            return new JsonResponse(['message' => 'Invalid data.'], 400);
        }
        if (!is_numeric($data['amount'])) {
            return new JsonResponse(['message' => 'Invalid amount.'], 400);
        }
        $sourceAccount = $em->getRepository(CashAccount::class)->find($data['sourceAccountId']);
        $destinationAccount = $em->getRepository(UserAccount::class)->find($data['destinationAccountId']);
        if (!$sourceAccount || !$destinationAccount) {
            return new JsonResponse(['message' => 'Account not found.'], 404);
        }
        $transaction = new Transaction();
        $transaction->setType(TransactionEnum::TRANSFER);
        $transaction->setSourceAccount($sourceAccount);
        $transaction->setDestinationAccount($destinationAccount);
        $transaction->setAmount((float) $data['amount']);
        $transaction->setCreatedBy($user);
        $em->persist($transaction);
        $em->flush();

        return new JsonResponse(['message' => 'Transfer initiated.'], 201);
    }
}
