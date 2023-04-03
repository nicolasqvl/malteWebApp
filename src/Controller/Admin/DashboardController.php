<?php

namespace App\Controller\Admin;

use App\Entity\Kit;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\KitProduct;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator){}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(KitCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MalteWebApp');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa-solid fa-truck-medical');

        yield MenuItem::section('MES ÉQUIPES');
            yield MenuItem::linkToCrud('Équipes', 'fa-solid fa-people-group', Team::class);

        yield MenuItem::section('MES LOTS');
            yield MenuItem::linkToCrud('Lots', 'fa-solid fa-suitcase-medical', Kit::class);
            yield MenuItem::linkToCrud('Contenu des lots', 'fa-solid fa-list-ul', KitProduct::class);

        yield MenuItem::section('MATÉRIELS ET CATÉGORIES');
            yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-list-ul', Category::class);
            // yield MenuItem::linkToCrud('Créer une catégorie', 'fa-solid fa-plus', Category::class)->setAction(Crud::PAGE_NEW);
            yield MenuItem::linkToCrud('Matériels', 'fa-solid fa-stethoscope', Product::class);
            // yield MenuItem::linkToCrud('Créer un produit', 'fa-solid fa-plus', Product::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('MES UTILISATEURS');
            yield MenuItem::linkToCrud('Comptes', 'fa-solid fa-user', User::class);
            // yield MenuItem::linkToCrud('Créer un compte', 'fa-solid fa-user-plus', User::class)->setAction(Crud::PAGE_NEW);

        yield MenuItem::section('COMMANDES');
            yield MenuItem::linkToCrud('Liste des commandes', 'fa-solid fa-list-check', Order::class);

    }
}
