<?php

namespace App\Controller\Admin;

use App\Entity\Sms;
use App\Repository\SmsRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SmsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sms::class;
    }

    public const EXPORT = 'export';

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new(self::EXPORT)
            ->linkToCrudAction('exportSms')
            ->setCssClass('btn btn-info');

        return $actions
        ->add(Crud::PAGE_INDEX, $duplicate)
        ->disable('new', 'delete', 'edit')
        ->setPermission(Action::NEW, 'ROLE_ADMIN')
        ;
    }

    /**
     * Je vais exporter les données de la table sms
     */
    public function exportSms(SmsRepository $smsRepository)
    {
        // Je récupère toutes les données de la table sms
        $sms = $smsRepository->findAll();

        // Je vais créer un fichier csv
        $file = fopen('export.csv', 'w');

        // Je vais écrire dans le fichier csv
        foreach ($sms as $sms) {
            fputcsv($file, [
                        $sms->getId(), 
                        $sms->getTelephone(), 
                        $sms->getNom(), 
                        $sms->getPrenom()
                    ]);
        }

        // Je ferme le fichier csv
        fclose($file);

        // Je vais télécharger le fichier csv
        return $this->file('export.csv');
    }
}
