<?php

namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellProfileSelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //  dump($options['spells']);
        $builder
            ->add(
                'character_spells',
                EntityType::class,
                [
                    'class' => CharacterSpell::class,
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $options['spells'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => SpellProfile::class,
                    'spells' => [],
                ]
            )
            ->addAllowedTypes('spells', ['array', 'AfmLibre\Pathfinder\Entity\CharacterSpell[]'])
            ->setRequired('spells');
    }
}
