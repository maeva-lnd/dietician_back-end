<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    public function __construct(
        private Security $security,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
        private RecipeRepository $recipeRepository
    ){
    }

    #[Route('secureapi/review', name: 'review', methods: ['POST'])]
    public function createReview(Request $request): JsonResponse
    {
        $review = $this->serializer->deserialize($request->getContent(), Review::class,'json');
        $user= $this->security->getUser();
        $review->setUser($user);
        $review->setDate(new \DateTime());
        $recipe = $this->recipeRepository->find($request->toArray()['recipe_id']);
        $review->setRecipe($recipe);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        $jsonReview = $this->serializer->serialize($review, 'json', ['groups' => 'getReview']);

        return new JsonResponse($jsonReview, Response::HTTP_OK, [], true);
    }
}
