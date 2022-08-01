<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ApiLoginController extends AbstractController
{
    public function __construct(private readonly JWTTokenManagerInterface $JWTManager)
    {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        throw new \LogicException('it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        
    }

    public function getTokenUser(User $user): JsonResponse
    {
        return new JsonResponse(['token' => $this->JWTManager->create($user)]);
    }
}
