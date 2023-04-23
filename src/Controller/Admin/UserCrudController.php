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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username', 'Nom');
        yield EmailField::new('email', 'E-mail');
        yield TextField::new('plainPassword', 'Mot de passe')
            ->onlyWhenCreating()
            ->setRequired(true)
            ->hideOnIndex();
        yield TextField::new('plainPassword', 'Modifier le mot de passe')
            ->onlyWhenUpdating()
            ->setRequired(false)
            ->hideOnIndex();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            yield TextField::new('unit', 'UDIOM')
                ->hideOnForm();
            yield AssociationField::new('unit', 'UDIOM')
                ->hideOnIndex()
                ->hideOnDetail();
            yield ArrayField::new('roles', 'Rôle');
            yield ArrayField::new('roles', 'Ajoutez un nouvel élément et indiquez le rôle : ROLE_ADMIN')
                ->hideOnIndex()
                ->hideOnDetail();
        }
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
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-pencil')
                    ->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-trash-can')
                    ->setLabel(false);
            });
    }

    // Gives to new user the user's unit (if is not SUPER_ADMIN) and send encrypted password
    public function persistEntity(EntityManagerInterface $em, $user): void
    {
        // if (!$entityInstance instanceof User) return;

        if(!$this->isGranted('ROLE_SUPER_ADMIN')){
            $connectedUser = $this->getUser()->getUnit();
            $user->setUnit($connectedUser);
            $this->encodePassword($user);
            parent::persistEntity($em, $user);
        }

        $this->encodePassword($user);
        parent::persistEntity($em, $user);
    }

    public function updateEntity(EntityManagerInterface $em, $user): void
    {
        // if (!$entityInstance instanceof User) return;

        $this->encodePassword($user);
        parent::updateEntity($em, $user);

        // if(!empty($userPass)){
        //     $this->encodePassword($entityInstance);
        //     parent::updateEntity($entityManager, $entityInstance);
        // }else{
        //     $entityInstance->setPassword($entity->getPassword());
        // }
        // parent::updateEntity($entityManager, $entityInstance);
    }

    // Only display user of the same unit as the admin
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $defaultQueryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $defaultQueryBuilder;
        }

        return $defaultQueryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
    }

    // Password encryption function
    private function encodePassword(User $user)
    {
        if ($user->getPlainPassword() !== null) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
}
