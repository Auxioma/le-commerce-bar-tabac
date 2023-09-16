<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Jeux;

class JeuxFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jeux = new Jeux();
        $jeux->setLabel("Gagné");
        $jeux->setValue('1');
        $jeux->setQuestion("Vous avez gagné un cafée !");
        $manager->persist($jeux);

        for ($i = 2; $i <= 13; $i++) {
            $jeux = new Jeux();
            $jeux->setLabel("Perdu");
            $jeux->setValue('0');
            $jeux->setQuestion("Vous avez perdu !");
            $manager->persist($jeux);
        }
        $manager->flush();
    }
}
