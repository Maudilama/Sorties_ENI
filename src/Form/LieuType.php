<?php

namespace App\Form;

use App\Entity\Lieu;

use App\Entity\Ville;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;



class LieuType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'class' => Lieu::class,
                'label' => 'Lieu',
                'required' => true,
                'attr' => [
                    'data-prototype' => $this->getLieuPrototype(),
                ],

            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue :',
                'scale' => 6,
                'required' => false,
                'attr' => [
                    'readonly' => true, // make this field read-only
                ],
            ])
            ->add('latitude', CustomFloatType::class, [
                'label' => 'Latitude :',
                'required' => false,
                'scale' => 6,
                'attr' => [
                    'readonly' => true, // make this field read-only
                ],

            ])
            ->add('longitude', CustomFloatType::class, [
                'label' => 'Longitude :',
                'scale' => 6,
                'required' => false,
                'attr' => [
                    'readonly' => true, // make this field read-only
                ],
            ])
            ->add('ville', VilleType::class, [
                //'class'=>Ville::class,
                'label' => false,
                'scale' => 6,
                'required' => false,
                'attr' => [
                    'readonly' => true, // make this field read-only
                ],
            ]);


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }





}

