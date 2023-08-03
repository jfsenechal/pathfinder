<?php


namespace AfmLibre\Pathfinder\Form;

use AfmLibre\Pathfinder\Form\Event\AddFieldClassSearchSubscriber;
use AfmLibre\Pathfinder\Level\LevelUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchSpellForFavoritesType extends AbstractType
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
                'level',
                ChoiceType::class,
                [
                    'choices' => LevelUtils::getSpellLevels(),
                    'placeholder' => 'Niveau',
                    'required' => false,
                ]
            );
        $formBuilder->addEventSubscriber(new AddFieldClassSearchSubscriber());
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([]);
    }
}
