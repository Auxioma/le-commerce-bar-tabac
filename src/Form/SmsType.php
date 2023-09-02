<?php

namespace App\Form;

use App\Entity\Sms;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class SmsType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('Nom'),
                    'class' => 'border-theme',
                ],
            ])
            ->add('Prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('Prénom'),
                    'class' => 'border-theme',
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->translator->trans('Numéro de téléphone'),
                    'class' => 'border-theme',
                ],
            ])

            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sms::class,
        ]);
    }
}
