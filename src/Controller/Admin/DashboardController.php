<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator
    ){
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Panneau d\'administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Patients');
        yield MenuItem::linkToCrud('Allergènes', '',  AllergenCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Régimes alimentaires', '', DietCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Recettes', '', RecipeCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Messages', '', ContactCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Avis', '', ReviewCrudController::getEntityFqcn());
    }
}
