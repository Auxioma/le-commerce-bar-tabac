<?php

// src/DataFixtures/HoraireFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Horaire;

class HoraireFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            ['vendredi', '07:00–19:30'],
            ['samedi', '07:00–19:30'],
            ['dimanche', '08:00–18:30'],
            ['lundi', '07:00–19:30'],
            ['mardi', '07:00–19:30'],
            ['mercredi', '07:00–19:30'],
            ['jeudi', '07:00–19:30'],
        ];

        foreach ($data as $item) {
            $horaire = new Horaire();
            $horaire->setJours($item[0]);
            $horaire->setHoraireOuverture($item[1]);
            $manager->persist($horaire);
        }

        $manager->flush();
    }
}
