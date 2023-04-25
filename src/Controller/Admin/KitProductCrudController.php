<?php

namespace App\Controller\Admin;

use App\Entity\KitProduct;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class KitProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return KitProduct::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('kit', 'Lot')
            ->hideOnForm();
        yield AssociationField::new('kit', 'Lot')
            ->hideOnIndex()
            // Only display in form kit of the same unit as the admin
            ->setQueryBuilder(function ($queryBuilder) {
                return $queryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
            });
        yield TextField::new('product', 'Matériel')
            ->hideOnForm();
        yield AssociationField::new('product', 'Matériel')
            ->hideOnIndex();
        yield IntegerField::new('product_quantity', 'Quantité')
            ->setRequired(true)
            ->setNumberFormat("#;");
        yield IntegerField::new('product_quantity_required', 'Quantité requise')
            ->setRequired(true);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un lot')
            ->setEntityLabelInPlural('Contenu des lots')
            // Delete list of actions in index page
            ->showEntityActionsInlined()
            ->overrideTemplate('crud/index', 'admin/kitProduct/field.html.twig')
            // Customization of detail's title
            ->setPageTitle('edit', fn ( KitProduct $kitProduct) => sprintf('Modifier le contenu du lot : <b>%s</b>', $kitProduct->getKit()))
            // Customization of create's title
            ->setPageTitle('new', 'Ajouter du contenu dans un lot');
    }

    // ----- Customization of actions icons and create button
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-pencil')
                    ->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-trash-can')
                    ->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Ajouter dans un lot');
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('kit', 'Lot')
            ->add('product', 'Matériel');
    }

    // Gives to new kitProduct the user's unit 
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if(!$entityInstance instanceof KitProduct) return;

        $connectedUser = $this->getUser()->getUnit();
        $entityInstance->setUnit($connectedUser);
        $em->persist($entityInstance);
        $em->flush();

    }

    // Only display kitProduct of the same unit as the admin
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $defaultQueryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $defaultQueryBuilder->andWhere('entity.unit = :connectedUser')->setParameter('connectedUser', $this->getUser()->getUnit());
    }
}
