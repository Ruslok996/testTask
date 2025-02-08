<?php

namespace App\Entity\Cash;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Account\Account;
use App\Entity\User\User;
use App\Enum\TransactionEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
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
    normalizationContext: ['groups' => ['transaction:read']],
    denormalizationContext: ['groups' => ['transaction:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['type' => 'exact', 'isCanceled' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['transaction:read'])]
    private ?int $id = null;

    #[ORM\Column(enumType: TransactionEnum::class)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private TransactionEnum $type;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private Account $sourceAccount;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private Account $destinationAccount;

    #[ORM\Column(type: 'float')]
    #[Groups(['transaction:read', 'transaction:write'])]
    private float $amount;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['transaction:read', 'transaction:write'])]
    private bool $isCanceled = false;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['transaction:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['transaction:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private ?User $updatedBy = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): TransactionEnum
    {
        return $this->type;
    }

    public function setType(TransactionEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSourceAccount(): Account
    {
        return $this->sourceAccount;
    }

    public function setSourceAccount(Account $sourceAccount): self
    {
        $this->sourceAccount = $sourceAccount;

        return $this;
    }

    public function getDestinationAccount(): Account
    {
        return $this->destinationAccount;
    }

    public function setDestinationAccount(Account $destinationAccount): self
    {
        $this->destinationAccount = $destinationAccount;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function isCanceled(): bool
    {
        return $this->isCanceled;
    }

    public function setCanceled(bool $isCanceled): self
    {
        $this->isCanceled = $isCanceled;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
