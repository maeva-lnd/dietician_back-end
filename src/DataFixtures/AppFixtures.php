<?php

namespace App\DataFixtures;

use App\Entity\Allergen;
use App\Entity\Contact;
use App\Entity\Diet;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Recipe;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des allergènes
        $listAllergen = [];
        for ($i=0; $i < 10; $i++) {
            $allergen = new Allergen();
            $allergen->setName('Allergène ' . $i);
            $listAllergen[]= $allergen;
            $manager->persist($allergen);
        }

        // Création des régimes alimentaires
        $listDiet = [];
        for ($i=0; $i < 5; $i++) {
            $diet = new Diet();
            $diet->setName('Régime alimentaire ' . $i);
            $listDiet[] = $diet;
            $manager->persist($diet);
        }

        // Création de recettes
        $time = new \DateTime();
        $listRecipe = [];
        for ($i=0; $i < 12; $i++) {

            $recipe = new Recipe();
            $recipe->setPicture('brownie.jpg');
            $recipe->setTitle('Titre ' . $i);
            $recipe->setDescription($this->faker->paragraph(2));
            $recipe->setPrepTime($time);
            $recipe->setCookingTime($time);
            $recipe->setRestTime($time);
            $recipe->setIngredients($this->faker->paragraph(rand(5, 15)));
            $recipe->setInstructions($this->faker->paragraph(rand(6,12)));
            $recipe->setIsOnlyForUser($this->faker->randomElement([true, false]));

            $nbAllergen = rand(0,count($listAllergen)-1);
            for ($j=0; $j <$nbAllergen; $j++) {
                $recipe->addAllergen($listAllergen[$j]);
            }

            $nbDiet = rand(0,count($listDiet)-1);

            for ($j=0; $j <$nbDiet; $j++) {
                $recipe->addDiet($listDiet[$j]);
            }

            $manager->persist($recipe);
            $listRecipe[] = $recipe;
        }

        // Création de users lambda
        $listUser = [];
        for ($i=0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail("user" . $i . "@test.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "3X3q6tQm"));
            $user->setFirstname("Prénom " . $i);
            $user->setLastname("Nom " . $i);
            $user->setPhone("0665111111");
            $manager->persist($user);
            $listUser[] = $user;
        }

        $nbAllergen = rand(0,count($listAllergen)-1);
        for ($j=0; $j <$nbAllergen; $j++) {
            $user->addAllergen($listAllergen[$j]);
        }

        $nbDiet = rand(0,count($listDiet)-1);

        for ($j=0; $j <$nbDiet; $j++) {
            $user->addDiet($listDiet[$j]);
        }


        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@test.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "aeS4e55W"));
        $userAdmin->setFirstname("Sandrine");
        $userAdmin->setLastname("Coupart");
        $userAdmin->setPhone("0707071111");
        $manager->persist($userAdmin);

        // Création de messages du formulaire de contact
        $contactMessage = new Contact();
        $contactMessage->setFirstname("John");
        $contactMessage->setLastname("Doe");
        $contactMessage->setEmail("jdoe@test.com");
        $contactMessage->setPhone("0721123243");
        $contactMessage->setMessage($this->faker->paragraph(6));
        $manager->persist($contactMessage);

        // Création d'avis
        $date = new \DateTime();
        for ($i=0; $i < 3; $i++) {
            $review = new Review();
            $review->setNote(rand(1, 5));
            $review->setComment($this->faker->sentence);
            $review->setDate($date);
            $review->setUser($listUser[array_rand($listUser)]);
            $review->setRecipe($listRecipe[array_rand($listRecipe)]);
            $manager->persist($review);
        }

        $manager->flush();
    }
}
