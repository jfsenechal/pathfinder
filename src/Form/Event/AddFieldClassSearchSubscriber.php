<?php

namespace AfmLibre\Pathfinder\Form\Event;

use AfmLibre\Pathfinder\Entity\ClassT;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddFieldClassSearchSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $dataForm = $event->getData();
        /**
         * @var ClassT $class
         */
        $class = $dataForm['class'];

        $form->add(
            'class',
            EntityType::class,
            [
                'class' => ClassT::class,
                'required' => false,
                'placeholder' => 'Sélectionnez une classe',
                'choices' => [$class],
            ]
        );
    }
}