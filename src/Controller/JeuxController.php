<?php

namespace App\Controller;

use DateTime;
use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Bridge\AllMySms\AllMySmsOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JeuxController extends AbstractController
{
    #[Route('/jeux', name: 'app_jeux')]
    public function index(JeuxRepository $jeux, Request $request, TexterInterface $texter): Response
    {
        // je verifie si la requete est une request POST
        if ($request->isMethod('POST')) {
            // je verifie si le token est valide
            if ($this->isCsrfTokenValid('jeux', $request->get('token'))) {
                 
                
                $nom = $request->get('nom');
                $prenom = $request->get('prenom');
                $telephone = $request->get('telephone');

                // date + 24h
                $date = new DateTime();
                $valable = $date->add(new \DateInterval('PT24H'));
                $valable = $valable->format('d-m-Y');

                $Message = "Bravo $nom $prenom vous avez gagné un café sur présentation de ce message ! Offre valable jusqu'au $valable";

                $sms = new SmsMessage('0686656358', $Message);

                $texter->send($sms);

                $this->addFlash('success', 'Un sms vous a été envoyé !');

            }
           
        }


        return $this->render('jeux/index.html.twig', [
            'jeux' => $jeux->findAll(),
        ]);
    }
}
