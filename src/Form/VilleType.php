<?php

namespace App\Form;


use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

           ->add('nom', EntityType::class,[
                'class'=>Ville::class,
                'label'=>'Ville',
               'required'=>true

           ])

            ->add('codePostal', EntityType::class, [
                'class'=>Ville::class,
                'choice_label'=>'Code Postal'
            ])
        ;
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
            'data_class' => Ville::class,
        ]);
    }
}
