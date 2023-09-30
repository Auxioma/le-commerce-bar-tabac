<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CategorieImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TalentController extends AbstractController
{
    #[Route('/talent', name: 'app_talent')]
    public function index(Request $request, CategorieImageRepository $images): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);


        return $this->render('talent/index.html.twig', [
            'form' => $form->createView(),
            'images' => $images->findBy([], ['UploadedAt' => 'DESC'], 1),
        ]);
    }
}
