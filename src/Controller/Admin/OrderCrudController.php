<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    // ----- Configuration to informations display
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('order_number', 'N° référence')
            ->hideOnDetail();
        yield DateTimeField::new('date', 'Date')
            ->hideOnDetail();
        yield TextField::new('kit', 'Lot');
        yield CollectionField::new('orderDetails', 'Matériel(s)')
            ->setTemplatePath('admin/orderDetail/detail.html.twig');
        yield TextField::new('declarer_name', 'Nom du déclarant')
            ->hideOnDetail();
        yield TextField::new('declarer_phone', 'Téléphone du déclarant')
            ->hideOnDetail();
        yield BooleanField::new('state', 'État')
            ->hideOnDetail();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['state'=>'asc', 'date'=>'asc'])
            // Delete list of actions in index page
            ->showEntityActionsInlined()
            // Customization of detail's title
            ->setPageTitle('detail', fn (Order $detail) => sprintf('Détail de la commande : <b>%s</b>', $detail->getOrderNumber()));
    }

    // ----- Customization of actions icons 
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-magnifying-glass')
                    ->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-trash-can')
                    ->setLabel(false);
            });
    }

    // Only display the orders for kit of the same unit as the admin
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $defaultQueryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $defaultQueryBuilder
            ->leftJoin('entity.kit', 'k')
            ->andWhere('k.unit = :connectedUser')
            ->setParameter('connectedUser', $this->getUser()->getUnit());
    }
}
