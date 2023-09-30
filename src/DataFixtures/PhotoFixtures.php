<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use App\Entity\CategorieImage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PhotoFixtures extends Fixture
{
    private UploaderHelper $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }
    
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 5; $i++) {
            $categorie = new CategorieImage();
            $categorie->setNom("Catégorie $i");
            $categorie->setSlug("categorie-$i");
            $categorie->setUploadedAt(new \DateTimeImmutable());
            $manager->persist($categorie);

            // Création de photos associées à chaque catégorie
            for ($j = 1; $j <= 10; $j++) {
                $photo = new Photo();
                $photo->setAlt("Image $j de Catégorie $i");
                $photo->setImageName( $i . '.jpg');
                // $photo->setUpdatedAt(new \DateTimeImmutable());
                $photo->setImageSize(1024);
                $photo->setCategorie($categorie);
                $manager->persist($photo);
            }
        }

        $manager->flush();
    }
}
