<?php

namespace App\Controller;

use App\Entity\Sms;
use App\Form\SmsType;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\HoraireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieImageRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
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


    #[Route([
        'fr' => '/',
        'de' => '/de', 
        'en' => '/en', 
        'bg' => '/bg',
        'hr' => '/hr',
        'da' => '/da',
        'es' => '/es',
        'et' => '/et', 
        'fi' => '/fi',
        'el' => '/el',
        'hu' => '/hu',
        'ga' => '/ga',
        'it' => '/it',
        'lv' => '/lv',
        'lt' => '/lt',
        'mt' => '/mt',
        'nl' => '/nl',
        'pl' => '/pl',
        'pt' => '/pt',
        'ro' => '/ro',
        'sk' => '/sk',
        'sl' => '/sl',
        'sv' => '/sv',
        'cs' => '/cs',
    ], name: 'app_home_page')]
    #[Cache(vary: ['Accept-Language'], public: true, expires: '+6 hour', maxage: '6', smaxage: '6', etag: '6')]
    public function index(HoraireRepository $horaire, Request $request, CategorieImageRepository $dossier): Response
    {
        $WeekNumber = strftime('%w');
        $lang = $request->getLocale();

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

          
            $numero = $formSms->get('telephone')->getData();
            $SMS->setTelephone($numero);
            

            $this->entityManager->persist($SMS);
            $this->entityManager->flush();
              dd($formSms);
    

            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_home_page');
        }

        /**
         * Formulaire de contact
         */
        if ($form->isSubmitted() && $form->isValid()){
            
            $email = (new TemplatedEmail())
                ->from($form->get('Email')->getData())
                ->to('contact@lecommercetabac.com')
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
            'horaire' => $horaire->findOneBy(['Lang' => $lang, 'WeekNumber' => $WeekNumber]),
            'ouvertures' => $horaire->findBy(['Lang' => $lang], ['WeekNumber' => 'ASC']),
            'form' => $form->createView(),
            'formSms' => $formSms->createView(),
            'photos' => $dossier->findBy([], ['id' => 'DESC'], 8),
        ]);
    }

    #[Route('/partenaire/{Slug}', name: 'app_dossier')]
    public function dossier(CategorieImageRepository $dossier, $Slug): Response
    {
        return $this->render('main/dossier.html.twig', [
           'photos' => $dossier->findBy(['Slug' => $Slug]),
        ]);
    }
}
