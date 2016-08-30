<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 30/08/16
 * Time: 14:04
 */

namespace AdminBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LastLoginSubscriber implements EventSubscriberInterface
{

    /** @var EntityManager */
    private $em;
    /**
     * LastLoginSubscriber constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastSeen(new \DateTime());
        $this->em->persist($user);
        $this->em->flush($user);
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'];
    }
}