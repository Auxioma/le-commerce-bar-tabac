<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CategorieImageRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TalentController extends AbstractController
{
    private MailerInterface $mailer;

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }

    #[Route('/talent', name: 'app_talent')]
    public function index(Request $request, CategorieImageRepository $images): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

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

            return $this->redirectToRoute('app_talent');
        }

        return $this->render('talent/index.html.twig', [
            'form' => $form->createView(),
            'images' => $images->findBy([], ['UploadedAt' => 'DESC'], 1),
        ]);
    }
}
