<?php

namespace App\Controller\Admin;

use App\Entity\Kit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class KitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Kit::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('name', 'Nom');
            yield AssociationField::new('team', 'Affectation');
            yield BooleanField::new('active', 'État opérationnel');

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un lot')
            ->setEntityLabelInPlural('Lots')
            // Delete list of actions in index page
            ->showEntityActionsInlined()
            // Customization of detail's title
            ->setPageTitle('edit', fn ( Kit $kit) => sprintf('Éditer le lot : <b>%s</b>', $kit->getName()));
    }

    // ----- Customization of actions icons 
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action){
                return $action
                    ->setIcon('fa-solid fa-pencil')
                    ->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action){
                return $action
                    ->setIcon('fa-solid fa-trash-can')
                    ->setLabel(false);
            });
    }
}

