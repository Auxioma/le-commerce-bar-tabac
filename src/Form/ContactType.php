<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Votre nom'),
                    'class' => 'form-control'
                ]
            ])
            ->add('Email', EmailType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Votre email'),
                    'class' => 'form-control'
                ]
            ])
            ->add('Telephone', TelType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Votre numéro de téléphone'),
                    'class' => 'form-control'
                ]
            ])
            ->add('Message', TextareaType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('Votre message'),
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
