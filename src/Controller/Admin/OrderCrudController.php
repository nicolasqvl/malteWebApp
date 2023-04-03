<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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
        yield TextField::new('reference_number', 'N° référence')
            ->hideOnDetail();
        yield DateTimeField::new('date', 'Date')
            ->hideOnDetail();
        yield TextField::new('kit', 'Lot');
        yield CollectionField::new('detailProductUseds', 'Matériel(s)')
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
            ->setEntityLabelInSingular('un lot')
            ->setEntityLabelInPlural('Lots')
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
}
