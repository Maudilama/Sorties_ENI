<?php

namespace App\Form;

use App\Entity\Lieu;

use Doctrine\DBAL\Types\FloatType;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class LieuType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'class'=>Lieu::class,
                'label'=>'Lieu',
                'required'=>true

            ])
            ->add('rue', TextType::class,[
                'label'=>'Rue :'
            ])

            ->add('latitude', CustomFloatType::class, [

                'label'=>'Latitude :'

            ])
            ->add('longitude', CustomFloatType::class, [
                'label'=>'Longitude :'
            ])

            ->add('ville', VilleType::class,[
                //'class'=>Ville::class,
                'label'=>false

            ]);
    }

    public function mapDataToForms($entity, $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['ville']->setData($entity->getVille());
        $forms['lieu']->setData($entity->getLieu());
        $forms['rue']->setData($entity->getRue());
        $forms['codePostal']->setData($entity->getcodePostal());
        $forms['latitude']->setData($entity->getLatitude());
        $forms['longitude']->setData($entity->getLongitude());
    }

    public function mapFormsToData($forms, &$entity):void
    {
        $forms = iterator_to_array($forms);
        $entity->setVille($forms['ville']->getData());
        $entity->setLieu($forms['lieu']->getData());
        $entity->setRue($forms['rue']->getData());
        $entity->setcodePostal($forms['codePostal']->getData());
        $entity->setLatitude($forms['latitude']->getData());
        $entity->setLongitude($forms['longitude']->getData());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}