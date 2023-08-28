<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param User $user
     * @return float|int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUserRecipes(User $user)
    {
        $userAllergens = [];
        $userDiets = [];
        foreach ($user->getAllergens() as $allergen) {
            $userAllergens[] = $allergen->getId();
        }
        foreach ($user->getDiets() as $diet) {
            $userDiets[] = $diet->getId();
        }

        $request = $this->createQueryBuilder('r')
            ->leftJoin('r.diets', 'd');

        if (!empty($userAllergens)) {
            $userAllergenRecipes = $this->createQueryBuilder('r')
                ->select('r.id')
                ->join('r.allergens', 'a')
                ->where('a.id in (:userAllergens)')
                ->setParameter('userAllergens', $userAllergens)
                ->getQuery()
                ->getArrayResult();
            $userAllergenRecipes = array_map('current', $userAllergenRecipes);

            $request->andWhere('r.id not in (:userAllergenRecipes)')
                ->setParameter('userAllergenRecipes', $userAllergenRecipes);
        }

        if (!empty($userDiets)) {
            $request
                ->andWhere('d.id in (:userDiets)')
                ->setParameter('userDiets', $userDiets);
        }

        return $request->getQuery()->getResult();
    }

    public function findPublicRecipe()
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.allergens', 'a')
            ->leftJoin('r.diets', 'd')
            ->where('r.isOnlyForUser = :isOnlyForUser')
            ->setParameter('isOnlyForUser', 0)
            ->getQuery()
            ->getResult()

        ;

        return $test->getResult();
    }
}
