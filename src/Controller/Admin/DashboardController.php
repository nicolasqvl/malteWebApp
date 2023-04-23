<?php

namespace App\Controller\Admin;

use App\Entity\Kit;
use App\Entity\Team;
use App\Entity\Unit;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\KitProduct;
use App\Controller\Admin\UnitCrudController;
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
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            $url = $this->adminUrlGenerator->setController(UnitCrudController::class)->generateUrl();
            return $this->redirect($url);
        }

        $url = $this->adminUrlGenerator->setController(KitCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MalteWebApp')
            ->disableDarkMode();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil', 'fa-solid fa-truck-medical', 'homepage');

        if($this->isGranted('ROLE_SUPER_ADMIN')){
            yield MenuItem::section('MES UNITÉS');
                yield MenuItem::linkToCrud('UDIOM', 'fa-solid fa-house-medical-flag', Unit::class);

            yield MenuItem::section('ENSEMBLE DES COMPTES');
                yield MenuItem::linkToCrud('Liste des comptes', 'fa-solid fa-user', User::class);

            yield MenuItem::section('MES PRODUITS ET CATÉGORIES');
                yield MenuItem::linkToCrud('Liste des catégories', 'fa-regular fa-rectangle-list', Category::class);
                yield MenuItem::linkToCrud('Liste des produits', 'fa-solid fa-stethoscope', Product::class);

        }else{

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
}
