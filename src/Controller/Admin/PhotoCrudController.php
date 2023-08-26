<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Photo::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            ImageField::new('imageName')->setBasePath('/assets/img/gallery')->onlyOnIndex(),       
            TextField::new('Alt', 'Titre de la photo'),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
        ];
    }

}
