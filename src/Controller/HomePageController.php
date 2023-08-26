<?php

namespace App\Controller;

use App\Entity\Sms;
use App\Form\SmsType;
use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\HoraireRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }


    #[Route('/', name: 'app_home_page')]
    public function index(HoraireRepository $horaire, Request $request, PhotoRepository $photo): Response
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

        // Création du formulaire de contact
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request); // Gère la requête

        $SMS = new Sms();
        $formSms = $this->createForm(SmsType::class, $SMS);
        $formSms->handleRequest($request);

        /**
         * Formulaire du SMS
         */
        if ($formSms->isSubmitted() && $formSms->isValid()) {
            // je vais formater le numéro de téléphone pour qu'il soit au format E164
            $numero = $formSms->get('telephone')->getData();
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            try {
                $swissNumberProto = $phoneUtil->parse($numero, "FR");
                $isValid = $phoneUtil->isValidNumber($swissNumberProto);
                if (!$isValid) {
                    $this->addFlash('danger', 'Le numéro de téléphone n\'est pas valide');
                    return $this->redirectToRoute('app_home_page');
                } else {
                    $numero = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
                    $SMS->setTelephone($numero);
                }
            } catch (\libphonenumber\NumberParseException $e) {
                var_dump($e);
            }

            $this->entityManager->beginTransaction();
            try {
                $this->entityManager->persist($SMS);
                $this->entityManager->flush();
                $this->entityManager->commit();
    
            } catch (\Exception $e) {
                $this->entityManager->rollback();
                throw $e;
            }
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_home_page');
        }

        /**
         * Formulaire de contact
         */
        if ($form->isSubmitted() && $form->isValid()){
            
            $email = (new TemplatedEmail())
                ->from($form->get('Email')->getData())
                ->to('noreply@lecommercebartabac.com')
                ->subject('Nouveaux message depuis le formulaire de contact')
                ->htmlTemplate('email/contact.html.twig')
                ->context(
                    [
                        'Nom' => $form->get('Nom')->getData(),
                        'Email' => $form->get('Email')->getData(),
                        'Telephone' => $form->get('Telephone')->getData(),
                        'Message' => $form->get('Message')->getData(),
                    ]
                )
            ;

            $this->mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('main/HomePage.html.twig', [
            'horaire' => $horaire->findOneBy(['Jours' => $joursFr[$dateEn]]),
            'form' => $form->createView(),
            'formSms' => $formSms->createView(),
            'photos' => $photo->findBy([], ['id' => 'DESC'], 8),
        ]);
    }
}
