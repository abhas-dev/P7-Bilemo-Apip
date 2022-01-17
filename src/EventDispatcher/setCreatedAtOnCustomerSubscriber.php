<?php

namespace App\EventDispatcher;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Client;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class setCreatedAtOnCustomerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setCreatedAt', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setCreatedAt(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        // On verifie qu'on est bien sur la requete qui nous interesse sinon pas besoin d'aller plus loin
        if (!$result instanceof Customer || Request::METHOD_POST !== $method) {
            return;
        }

        $result->setCreatedAt(new \DateTime());
    }
}