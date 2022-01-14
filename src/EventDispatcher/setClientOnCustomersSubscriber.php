<?php

namespace App\EventDispatcher;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Client;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class setClientOnCustomersSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setClient', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setClient(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $currentUser = $this->security->getUser();

        // On verifie qu'on est bien sur la requete qui nous interesse sinon pas besoin d'aller plus loin
        if (!$result instanceof Customer || Request::METHOD_POST !== $method || !$currentUser instanceof Client) {
           return;
        }

        $result->setClient($currentUser);
    }
}