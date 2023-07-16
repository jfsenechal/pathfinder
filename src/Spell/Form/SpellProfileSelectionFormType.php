<?php


namespace AfmLibre\Pathfinder\Spell\Form;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Spell\Dto\SpellProfileSelectionDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellProfileSelectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'spells',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $options['spells'],
                    'choice_label' => fn(?CharacterSpell $characterSpell) => $characterSpell instanceof \AfmLibre\Pathfinder\Entity\CharacterSpell ? $characterSpell->getSpell()->getName().' ('.$characterSpell->getLevel(
                        ).')' : '',
                ]
            )
            ->add(
                'quantities',
                CollectionType::class,
                [
                    'entry_type' => QuantityFormType::class,
                    'entry_options' => ['label' => false],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => SpellProfileSelectionDto::class,
                    'spells' => [],
                ]
            )
            ->addAllowedTypes('spells', ['array', 'AfmLibre\Pathfinder\Entity\CharacterSpell[]'])
            ->setRequired('spells');
    }

}
