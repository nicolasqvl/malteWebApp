<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('username', 'Nom');
            yield EmailField::new('email', 'E-mail');
            yield TextField::new('password', 'Mot de passe')
                ->hideOnIndex();

    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un utilisateur')
            ->setEntityLabelInPlural('Comptes utilisateurs')
            // Delete list of actions in index page
            ->showEntityActionsInlined()
            // Customization of detail's title
            ->setPageTitle('edit', fn (User $user) => sprintf('Modification de l\'utilisateur : <b>%s</b>', $user->getUserIdentifier()));
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

    // Gives to new user the user's unit 
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if(!$entityInstance instanceof User) return;

        $connectedUser = $this->getUser()->getUnit();
        $entityInstance->setUnit($connectedUser);
        $em->persist($entityInstance);
        $em->flush();

    }

    // Only display user of the same unit as the admin
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $defaultQueryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $defaultQueryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
    }
}
