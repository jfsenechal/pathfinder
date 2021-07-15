<?php

namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellProfileSelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'character_spells',
                CollectionType::class,
                [
                    'entry_type' => CharacterSpell::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SpellProfile::class,
            ]
        );
    }
}
