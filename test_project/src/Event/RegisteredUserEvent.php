<?php


namespace App\Event;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;


class RegisteredUserEvent extends Event
{
    public const NAME = 'user.register';
    private $registeredUser;
    public function __construct(User $regiseredUser )
    {
        $this->registeredUser = $regiseredUser;
    }
    public function getRegisteredUser()
    {
        return $this->registeredUser;
    }
}