<?php

namespace App\DataFixtures;

use App\Entity\Photo;
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

        for ($i = 1; $i < 9; $i++) {
            $photo = new Photo();
            $photo->setAlt('Photo ' . $i);

            $photo->setImageName( $i . '.jpg');

            $manager->persist($photo);
        }

        $manager->flush();
    }
}
