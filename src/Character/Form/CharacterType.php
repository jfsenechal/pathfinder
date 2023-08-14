<?php

namespace AfmLibre\Pathfinder\Character\Form;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Classes\Repository\ClassRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Leveling\LevelingEnum;
use AfmLibre\Pathfinder\Race\Repository\RaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
                    'required' => true,
                ]
            )
            ->add(
                'dexterity',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'constitution',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'intelligence',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'wisdom',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'charisma',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'race',
                EntityType::class,
                [
                    'placeholder' => '',
                    'class' => Race::class,
                    'query_builder' => fn (RaceRepository $raceRepository) => $raceRepository->getQl(),
                ]
            )
            ->add(
                'classT',
                EntityType::class,
                [
                    'placeholder' => '',
                    'class' => ClassT::class,
                    'query_builder' => fn (
                        ClassRepository $classTRepository
                    ) => $classTRepository->getQl(),
                ]
            )
            ->add(
                'select_level',
                ChoiceType::class,
                [
                    'label' => 'Level',
                    'placeholder' => '',
                    'choices' => array_combine(range(1, 20), range(1, 20)),
                ]
            )
            ->add(
                'point_by_level',
                ChoiceType::class,
                [
                    'label' => 'form.point_by_level.label',
                    'help' => 'Point attribué par le MD',
                    'required' => true,
                    'placeholder' => 'Choisissez',
                    'choices' => LevelingEnum::choicesForList(),
                ]
            )
            ->add(
                'sizeType',
                EnumType::class,
                [
                    'class' => SizeEnum::class,
                    'required' => true,
                ]
            )
            ->add(
                'hit_point',
                IntegerType::class,
                [
                    'label' => 'form.hit_point.label',
                    'required' => true,
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
