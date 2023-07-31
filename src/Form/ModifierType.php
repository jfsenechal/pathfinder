<?php

namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ability', EnumType::class, [
                'class' => ModifierListingEnum::class,
                'label' => 'Capacité influencée',
                'placeholder' => 'Sélectionnez',
            ])
            ->add('value', IntegerType::class, [

            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Modifier::class,
            ]
        );
    }

}
