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
            ->setDefaultSort(['name'=>'asc'])
            // Delete list of actions in index page
            ->showEntityActionsInlined();
    }

    // ----- Removal of delete and edit actions
    public function configureActions(Actions $actions): Actions
    {
        if($this->isGranted('ROLE_SUPER_ADMIN')){
            return $actions
                ->add(Crud::PAGE_EDIT, Action::INDEX)
                ->add(Crud::PAGE_NEW, Action::INDEX)
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

        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->disable(Action::DELETE, Action::EDIT);

        
    }
}
