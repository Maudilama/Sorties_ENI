<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnulerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('infosSortie', TextareaType::class, [
            'label' => 'Motif d\'annulation',
            'required' => true,
            'attr' => [
                'rows' => 5,
                'maxlength' => 500,
            ],
            'constraints' =>[
                new NotBlank([
                    'message' => 'Veuillez rentrer un motif d\'annulation !',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
