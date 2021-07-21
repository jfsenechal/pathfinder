<?php


namespace AfmLibre\Pathfinder\Spell\Form;


use AfmLibre\Pathfinder\Spell\Dto\QuantityDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'quantity',
                IntegerType::class,
                [
                    'label' => false,
                    'attr' => ['placeholder' => 'Quantité', 'class' => 'w-25'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => QuantityDto::class,
            ]
        );
    }
}
