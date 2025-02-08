<?php

namespace App\Entity\Account;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'cash' => CashAccount::class,
    'user' => UserAccount::class,
])]
abstract class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['account:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['account:read', 'account:write'])]
    protected float $balance = 0.0;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['account:read', 'account:write'])]
    protected bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
