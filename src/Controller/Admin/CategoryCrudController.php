<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setDefaultSort(['name'=>'asc']);
    }

    // ----- Removal of delete and edit actions
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE, Action::EDIT);
    }
}
