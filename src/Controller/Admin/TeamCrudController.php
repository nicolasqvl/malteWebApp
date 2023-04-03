<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('name', 'Nom');
            yield AssociationField::new('kit', 'Lot affecté')
                ->hideOnIndex();
            yield TextField::new('kit', 'Lot affecté')
                ->hideOnForm();

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une équipe')
            ->setEntityLabelInPlural('Équipes')
            // Delete list of actions in index page
            ->showEntityActionsInlined()
            // Customization of detail's title
            ->setPageTitle('edit', fn ( Team $team) => sprintf('Éditer l\'équipe : <b>%s</b>', $team->getName()));
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
