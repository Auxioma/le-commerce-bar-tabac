<?php

namespace App\Controller;

use App\Repository\HoraireRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(HoraireRepository $horaire): Response
    {
        $date    = DateTimeImmutable::createFromFormat('U', time());
        $dateEn  = $date->format('D');

        // Je met dans une variable le jour de la semaine en français
        $joursFr = [
            'Mon' => 'lundi',
            'Tue' => 'mardi',
            'Wed' => 'mercredi',
            'Thu' => 'jeudi',
            'Fri' => 'vendredi',
            'Sat' => 'samedi',
            'Sun' => 'dimanche',
        ];
        
        return $this->render('main/HomePage.html.twig', [
            'horaire' => $horaire->findOneBy(['Jours' => $joursFr[$dateEn]])
        ]);
    }
}
