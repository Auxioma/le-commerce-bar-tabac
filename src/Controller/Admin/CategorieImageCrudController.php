<?php

namespace App\Controller\Admin;

use App\Entity\CategorieImage;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;

class CategorieImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategorieImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            ImageField::new('imageName')->setBasePath('/assets/img/categorie')->onlyOnIndex(),       
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            TextField::new('Nom', 'Titre de la categorie'),
            SlugField::new('Slug')->setTargetFieldName('Nom')->hideOnIndex(),
        ];
    }
}
