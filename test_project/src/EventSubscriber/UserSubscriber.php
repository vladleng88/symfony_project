<?php


namespace App\EventSubscriber;


use App\Event\RegisteredUserEvent;
use App\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public $mailer;
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;

    }
    public static function getSubscribedEvents()
    {
        return [RegisteredUserEvent::NAME => 'onUserRegister'];
    }
    public function onUserRegister (RegisteredUserEvent $userEvent)
    {
        $this->mailer->sendConfirmationMessage($userEvent->getRegisteredUser());
    }
}