<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\UserJeux;
use App\Form\UserJeuxType;
use App\Repository\JeuxRepository;
use App\Repository\UserJeuxRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        // création du formulaire pour faire tourné la roue
        $UserJeux = new UserJeux();
        $form = $this->createForm(UserJeuxType::class, $UserJeux);

        return $this->render('jeux/index.html.twig', [
            'jeux' => $jeux->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api-jeux', name: 'app_joueur_jouer')]
    public function api(UserJeuxRepository $UserJeux, EntityManagerInterface $entityManager)
    {
        // je recupere les données envoyé par la requete ajax en JS
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $telephone = $_POST['telephone'];

        // formater tous les numéro de téléphone sous ce format 0606060606
        $telephone = str_replace(' ', '', $telephone);
        

        // je verifie si les données sont bien envoyé
        if ($nom && $prenom && $telephone) {
            // je vais vérifier que l'utilisateur est en BDD
            $user = $UserJeux->findOneBy([
                'Telephone' => $telephone,
            ]);

            // si l'utilisateur est en base de donnée, je vérifie si la date est inférieur à la date du jour + 24h
            if ($user) {
                $date = new DateTimeImmutable();
                $valable = $date->add(new \DateInterval('PT24H'));
                $valable = $valable->format('d-m-Y');

                // si la date est inférieur à la date du jour + 24h, je retourne un message d'erreur
                if ($user->getNumberGame()->format('d-m-Y') < $valable) {
                    return $this->json([
                        'success' => false,
                    ]);
                } else {
                    // sinon je retourne un message de succès
                    return $this->json([
                        'success' => true,
                    ]);
                }
            } else {
                // j'enregistre l'utilisateur en base de donnée
                $user = new UserJeux();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setTelephone($telephone);
                $user->setCreatedAt(new DateTimeImmutable());
                $user->setNumberGame(new DateTimeImmutable());

                $entityManager->persist($user);
                $entityManager->flush();

                // je retourne un message de succès
                return $this->json([
                    'success' => true,
                ]);
            }

        }
    }

    #[Route('/api-jeux-gagnant', name: 'app_joueur_gagnant')]
    public function gagnant(Request $request, TexterInterface $texter)
    {
        if ($request->isMethod('POST')) {
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $telephone = $request->get('telephone');

            // date + 24h
            $date = new DateTime();
            $valable = $date->add(new \DateInterval('PT24H'));
            $valable = $valable->format('d-m-Y');

            $Message = "Bravo $nom $prenom vous avez gagné un café sur présentation de ce message ! Offre valable jusqu'au $valable";
            $sms = new SmsMessage($telephone, $Message);

            $texter->send($sms);

            return $this->json([
                'smsenvoie' => true,
            ]);

            

        }
    }

}
