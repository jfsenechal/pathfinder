<?php

namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'strength',
                IntegerType::class,
                [
                    'label' => 'Force',
                    'required' => true,
                ]
            )
            ->add(
                'dexterity',
                IntegerType::class,
                [
                    'label' => 'Dextérité',
                    'required' => true,
                ]
            )
            ->add(
                'constitution',
                IntegerType::class,
                [
                    'label' => 'Constitution',
                    'required' => true,
                ]
            )
            ->add(
                'intelligence',
                IntegerType::class,
                [
                    'label' => 'Intelligence',
                    'required' => true,
                ]
            )
            ->add(
                'wisdom',
                IntegerType::class,
                [
                    'label' => 'Sagesse',
                    'required' => true,
                ]
            )
            ->add(
                'charisma',
                IntegerType::class,
                [
                    'label' => 'Charisme',
                    'required' => true,
                ]
            )
            ->add(
                'race',
                EntityType::class,
                [
                    'placeholder' => '',
                    'class' => Race::class,
                    'query_builder' => fn(RaceRepository $raceRepository) => $raceRepository->getQl(),
                ]
            )
            ->add(
                'classT',
                EntityType::class,
                [
                    'label' => 'Classe',
                    'placeholder' => '',
                    'class' => ClassT::class,
                    'query_builder' => fn(
                        ClassRepository $classTRepository
                    ) => $classTRepository->getQl(),
                ]
            )
            ->add(
                'select_level',
                ChoiceType::class,
                [
                    'label' => 'Niveau',
                    'placeholder' => '',
                    'choices' => array_combine(range(1, 20), range(1, 20)),
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Character::class,
            ]
        );
    }
}
