<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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
            ->add('duree', TimeType::class,[
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
            ])


            ->add('lieu',LieuType::class,[
                //'class'=>Lieu::class,
                'label'=>false

            ])

            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier la sortie',
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