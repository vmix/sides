<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $JWTManager
    )
    {
    }

    #[Route('/api/login', name: 'api_login', methods: 'POST')]
    public function index(UserInterface $user): JsonResponse
    {
        $session = new Session(new NativeSessionStorage(), new AttributeBag());

        if (null === $user->getUserIdentifier()) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->getTokenUser($user); // TODO: somehow create an API token for $user
        $session->set('access_token', $token);

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }

    public function getTokenUser(User $user): JsonResponse
    {
        return new JsonResponse(['token' => $this->JWTManager->create($user)]);
    }
}
