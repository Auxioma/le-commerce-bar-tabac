<?php

namespace App\Controller\Admin;

use App\Entity\Jeux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class JeuxCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Jeux::class;
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
