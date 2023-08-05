<?php

namespace AfmLibre\Pathfinder\User\Form;

use AfmLibre\Pathfinder\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'required' => true,
            ]
        )
            ->add(
                'first_name',
                TextType::class,
                [
                    'required' => true,
                ]
            )->add('email', EmailType::class)
            ->add(
                'plainPassword',
                TextType::class,
                [
                    'label' => 'Mot de passe',
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => ['ROLE_PATHFINDER' => 'ROLE_PATHFINDER'],
                    'multiple' => true,
                    'expanded' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
