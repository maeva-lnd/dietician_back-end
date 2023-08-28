<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RecipeCrudController extends AbstractCrudController
{

    public const RECIPE_BASE_PATH = 'upload/images/recipes';
    public const RECIPE_UPLOAD_DIR = 'public/upload/images/recipes';

    public static function getEntityFqcn(): string
    {
        return Recipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            BooleanField::new('isOnlyForUser', 'Active seulement pour les patients'),
            ImageField::new('picture', 'Image')
                ->setBasePath(self::RECIPE_BASE_PATH)
                ->setUploadDir(self::RECIPE_UPLOAD_DIR),
            TextField::new('title', 'Titre'),
            TextareaField::new('description', 'Description de la recette'),
            TimeField::new('prepTime','Temps de préparation'),
            TimeField::new('cookingTime','Temps de cuisson'),
            TimeField::new('restTime','Temps de repos'),
            AssociationField::new('allergens', 'Allergène(s)')
                 ->setFormTypeOptions([
                'by_reference' => false,
                 ]),
            AssociationField::new('diets', 'Régime(s) alimentaire(s)')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ]),
            TextEditorField::new('ingredients', 'Ingrédients'),
            TextEditorField::new('instructions', 'Etapes'),
        ];
    }
}
