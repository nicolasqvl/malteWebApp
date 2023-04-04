<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
        yield TextField::new('category', 'Nom')
            ->hideOnForm();
        yield AssociationField::new('category', 'Nom')
            ->hideOnIndex();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un matériel')
            ->setEntityLabelInPlural('Matériels')
            ->setDefaultSort(['name'=>'asc']);
    }

    // ----- Removal of delete and edit actions
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE, Action::EDIT);
    }
}
