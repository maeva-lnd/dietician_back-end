<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;


class RecipeController extends AbstractController
{
    public function __construct(
        private Security $security,
        private RecipeRepository $recipeRepository,
        private SerializerInterface $serializer
    ) {
    }

    // Fonction retournant les recettes publiques ou accessibles seulement aux patients
    #[Route('/api/recipes', name: 'recipes', methods: ['GET'])]
    public function getAllRecipes( )
    {
        $user = $this->security->getUser();

        if (is_null($user)) {
            $recipeList = $this->recipeRepository->findPublicRecipe();
        } else {
            $recipeList = $this->recipeRepository->findUserRecipes($user);
        }

        $jsonRecipeList = $this->serializer->serialize($recipeList, 'json', ['groups' => ['getRecipe']]);
        return new JsonResponse($jsonRecipeList, Response::HTTP_OK, [], true);
    }

    // Fonction retournant une seule recette dont l'ID est passé en paramètre
    #[Route('/api/recipe/{id}', name: 'recipe', methods: ['GET'])]
    public function getRecipe(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        $recipe = $this->recipeRepository->findOneBy(['id' => $id]);

        if (
            (!is_null($user) && !$recipe->isIsOnlyForUser())
            || (is_null($user))
        ) {
            $jsonRecipeList = $this->serializer->serialize($recipe, 'json', ['groups' => 'getRecipe']);
            return new JsonResponse($jsonRecipeList, Response::HTTP_OK, [], true);
        }

        return new JsonResponse("Dont have access on this recipe", Response::HTTP_FORBIDDEN, [], true);
    }
}
