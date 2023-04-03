<?php

namespace App\Controller\Admin;

use App\Entity\KitProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class KitProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return KitProduct::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
