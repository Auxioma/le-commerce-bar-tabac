<?php

namespace App\Controller;

use App\Repository\JeuxRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Bridge\AllMySms\AllMySmsOptions;
use Symfony\Component\Notifier\TexterInterface;

class JeuxController extends AbstractController
{
    #[Route('/jeux', name: 'app_jeux')]
    public function index(JeuxRepository $jeux, Request $request, TexterInterface $texter): Response
    {
        // je verifie si la requete est une request POST
        if ($request->isMethod('POST')) {
            // je verifie si le token est valide
            if ($this->isCsrfTokenValid('jeux', $request->get('token'))) {
                 
                $sms = new SmsMessage('+33766068003', 'My message');

                $options = (new AllMySmsOptions())
                    ->alerting(1)
                    ->campaignName('API')
                    ->cliMsgId('test_cli_msg_id')
                    ->date('2023-05-23 23:47:25')
                    ->simulate(1)
                    ->uniqueIdentifier('unique_identifier')
                    ->verbose(1)
                    // ...
                    ;
                
                // Add the custom options to the sms message and send the message
                $sms->options($options);
                
                // $texter->send($sms);

            }
           
        }


        return $this->render('jeux/index.html.twig', [
            'jeux' => $jeux->findAll(),
        ]);
    }
}
