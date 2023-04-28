<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;





class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,
                              array$options,
                                ): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                /*'label'=>'Date et heure de la sortie :',
                'widget'=>'single_text',
                'form_attr'=>'yy-MM-dd',*/



            ])
            ->add('dateLimiteInscription', DateTimeType::class,[
                /*'label'=>"Date limite d'inscripton :",
                'widget'=>'single_text',
                'form_attr'=>'yy-MM-dd',*/


            ])
            ->add('nbInscriptionsMax', IntegerType::class,[
                'label'=>'Nombre de places :'
            ])
            ->add('duree', IntegerType::class,[
                'label'=>'Durée :',
                'form_attr'=>'HH:mm'
            ])
            ->add('infosSortie', TextType::class, [
                'label'=>'Description et infos :'
            ])

            ->add('campus', EntityType::class, [
                'label'=>'Campus',
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'required'=>false



            ]);

            $builder
            ->add('lieu', LieuType::class,[
                'label'=>false

            ]);



    }








    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            //'data_class'=> Lieu::class

        ]);
    }
}
