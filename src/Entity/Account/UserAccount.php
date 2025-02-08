<?php

namespace App\Entity\Account;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER') or is_granted('ROLE_USER')",
            securityMessage: 'Access denied.'
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER')",
            securityMessage: 'Access denied.'
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIAL_MANAGER') or object.getOwner() == user",
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
    normalizationContext: ['groups' => ['userAccount:read']],
    denormalizationContext: ['groups' => ['userAccount:write']]
)]
class UserAccount extends Account
{
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userAccounts')]
    #[Groups(['userAccount:read', 'userAccount:write'])]
    private ?User $owner = null;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
