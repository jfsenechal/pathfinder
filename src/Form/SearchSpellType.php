<?php


namespace AfmLibre\Pathfinder\Form;


use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Level\LevelUtils;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSpellType extends AbstractType
{
    /**
     * @param FormBuilderInterface $formBuilder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add(
                'name',
                SearchType::class,
                [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Nom',
                        'autocomplete' => 'off',
                    ],
                ]
            )
            ->add(
                'class',
                EntityType::class,
                [
                    'class' => ClassT::class,
                    'required' => false,
                    'placeholder' => 'Sélectionnez une classe',
                    'query_builder' =>
                        fn(ClassRepository $classTRepository) => $classTRepository->getQl(),
                ]
            )
            ->add(
                'level',
                ChoiceType::class,
                [
                    'choices' => LevelUtils::getSpellLevels(),
                    'placeholder' => 'Niveau',
                    'required' => false,
                ]
            );
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([]);
    }
}
