<?php

namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Spell\Dto\SpellSelectionDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'spells',
            EntityType::class,
            [
                'class' => Spell::class,
                'choices' => $options['spells'],
                'choice_label' => fn(Spell $spell) => $spell->getName(),
                'multiple' => true,
                'expanded' => true,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => SpellSelectionDto::class,
                    'spells' => [],
                ]
            )
            ->addAllowedTypes('spells', ['array', 'AfmLibre\Pathfinder\Entity\Spell[]'])
            ->setRequired('spells');
    }
}
