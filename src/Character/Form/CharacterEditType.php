<?php

namespace AfmLibre\Pathfinder\Character\Form;

use AfmLibre\Pathfinder\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('select_level');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Character::class,
            ]
        );
    }

    public function getParent()
    {
        return CharacterType::class;
    }
}
