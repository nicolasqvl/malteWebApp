<?php

namespace App\Controller\Admin;

use App\Entity\Kit;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class KitCrudController extends AbstractCrudController
{

    public function __construct(private string $uploadDir)
    {
        
    }

    public static function getEntityFqcn(): string
    {
        return Kit::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('name', 'Nom');
            yield TextField::new('original_unit', 'Unité d\'origine');
            yield TextField::new('team', 'Affectation')
                ->hideOnForm();
            yield AssociationField::new('team', 'Affectation')
                ->hideOnIndex()
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
                });
            yield ImageField::new('qrName', 'Qr-Code')
                ->setBasePath($this->uploadDir)
                ->hideOnForm();
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

    // Gives to new kit the user's unit 
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if(!$entityInstance instanceof Kit) return;

        $connectedUser = $this->getUser()->getUnit();
        $kitName = $entityInstance->getName();

        $entityInstance->setUnit($connectedUser);
        $entityInstance->setQrName("{$kitName}.png");
        $em->persist($entityInstance);
        $em->flush();
    }

    // Only display kit of the same unit as the admin
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $defaultQueryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $defaultQueryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
    }
}

