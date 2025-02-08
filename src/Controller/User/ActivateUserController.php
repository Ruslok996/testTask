<?php

namespace App\Controller\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ActivateUserController extends AbstractController
{
    #[Route('/api/users/{id}/activate', name: 'activate_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(User $user, EntityManagerInterface $em): JsonResponse
    {
        $user->setIsActive(true);
        $em->flush();

        return new JsonResponse(['message' => 'User activated successfully.'], 200);
    }
}
