<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer

    ){
    }

    #[Route('/secureapi/user', name: 'app_user', methods: ['GET'])]
    public function index(): Response
    {
        $user= $this->security->getUser();
        $jsonUser = $this->serializer->serialize($user, 'json',['groups' => 'getUser']);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }
}
