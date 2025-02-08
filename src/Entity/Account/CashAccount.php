<?php

namespace App\Entity\Account;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Cash\CashRegister;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
    ],
    normalizationContext: ['groups' => ['cashAccount:read']],
    denormalizationContext: ['groups' => ['cashAccount:write']]
)]
class CashAccount extends Account
{
    #[ORM\ManyToOne(targetEntity: CashRegister::class)]
    #[Groups(['cashAccount:read', 'cashAccount:write'])]
    private ?CashRegister $cashRegister = null;

    public function getCashRegister(): ?CashRegister
    {
        return $this->cashRegister;
    }

    public function setCashRegister(?CashRegister $cashRegister): self
    {
        $this->cashRegister = $cashRegister;

        return $this;
    }
}
