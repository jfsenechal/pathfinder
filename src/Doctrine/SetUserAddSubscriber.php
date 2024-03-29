<?php

namespace AfmLibre\Pathfinder\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsDoctrineListener(event: Events::prePersist)]
final class SetUserAddSubscriber
{
    public function __construct(private Security $security, private PropertyUtil $propertyUtil)
    {
    }
    public function prePersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$this->propertyUtil->getPropertyAccessor()->isWritable($object, 'userAdd')) {
            return;
        }

        $this->setUserAdd($object);
    }
    private function setUserAdd(object $entity): void
    {
        //for loading fixtures
        if ($entity->getUserAdd()) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            throw new Exception('You must be login');
        }

        if ($user) {
            $entity->setUserAdd($user->getUserIdentifier());
        }
    }
}
