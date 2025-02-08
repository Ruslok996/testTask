<?php

namespace App\Controller\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeactivateUserController extends AbstractController
{
    #[Route('/api/users/{id}/deactivate', name: 'deactivate_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(User $user, EntityManagerInterface $em): JsonResponse
    {
        $user->setIsActive(false);
        $em->flush();

        return new JsonResponse(['message' => 'User deactivated successfully.'], 200);
    }
}
