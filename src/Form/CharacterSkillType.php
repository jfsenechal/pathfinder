<?php


namespace AfmLibre\Pathfinder\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterSkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([]);
    }
}
