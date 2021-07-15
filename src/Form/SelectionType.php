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
    /**
     * @param FormBuilderInterface $formBuilder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'spells',
            EntityType::class,
            [
                'class' => Spell::class,
                'choices' => $options['spells'],
                'multiple' => true,
                'expanded' => true,
            ]
        );
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver
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
