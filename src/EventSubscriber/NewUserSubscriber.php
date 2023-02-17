<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\EmailService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class NewUserSubscriber implements EventSubscriberInterface
{
    private $em;
    public function __construct(EmailService $em)
    {
        $this->em=$em;
    }

    public function postPersist (LifecycleEventArgs $arg/* , EmailService $es */): void
    {
        $user = $arg->getObject();
        if ($user instanceof User) {
            $this->em->sendWelcomeEmail($user);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
        ];
    }
}
