<?php


namespace AfmLibre\Pathfinder\Spell\Form;

use AfmLibre\Pathfinder\Entity\Spell;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpellEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'summary',
                TextareaType::class,
                [
                    'label' => 'Summary',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Spell::class,
            ]
        );
    }
}
