<?php


namespace AfmLibre\Pathfinder\Form;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfileCharacterSpell;
use AfmLibre\Pathfinder\Spell\CharacterSpellToBoolTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellProfileSpellType extends AbstractType
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'quantity',
                NumberType::class,
                [
                    'required' => true,
                    'label' => 'Quantité',
                ]
            )
            ->add(
                'characterSpell',
                EntityType::class,
                [
                    'label' => 'Sort',
                    'class' => CharacterSpell::class,
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
        $optionsResolver->setDefaults(
            [
                'data_class' => SpellProfileCharacterSpell::class,
            ]
        );
    }
}
