<?php

namespace App\Entity\Cash;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Account\CashAccount;
use App\Repository\Cash\CashRegisterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CashRegisterRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access Denied.'
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access Denied.'
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access Denied.'
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access Denied.'
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access Denied.'
        ),
    ],
    normalizationContext: ['groups' => ['cashRegister:read']],
    denormalizationContext: ['groups' => ['cashRegister:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'isActive' => 'exact'])]
class CashRegister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cashRegister:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: false)]
    #[Groups(['cashRegister:read', 'cashRegister:write'])]
    private ?string $name = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['cashRegister:read', 'cashRegister:write'])]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, CashAccount>
     */
    #[ORM\OneToMany(targetEntity: CashAccount::class, mappedBy: 'cashRegister')]
    #[Groups(['cashRegister:read'])]
    private Collection $cashAccounts;

    public function __construct()
    {
        $this->cashAccounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, CashAccount>
     */
    public function getCashAccounts(): Collection
    {
        return $this->cashAccounts;
    }

    public function addCashAccount(CashAccount $cashAccount): static
    {
        if (!$this->cashAccounts->contains($cashAccount)) {
            $this->cashAccounts->add($cashAccount);
            $cashAccount->setCashRegister($this);
        }

        return $this;
    }

    public function removeCashAccount(CashAccount $cashAccount): static
    {
        if ($this->cashAccounts->removeElement($cashAccount)) {
            // set the owning side to null (unless already changed)
            if ($cashAccount->getCashRegister() === $this) {
                $cashAccount->setCashRegister(null);
            }
        }

        return $this;
    }
}
