<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Event\UserCheckerEvent;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class UserCheckerEventTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $user = $this->createMock(UserInterface::class);
        $userCheckerEvent = new UserCheckerEvent($user);

        $this->assertInstanceOf(Event::class, $userCheckerEvent);
        $this->assertEquals($user, $userCheckerEvent->getUser());
        $this->assertInstanceOf(UserInterface::class, $userCheckerEvent->getUser());
        $this->assertTrue($userCheckerEvent->isAuthorized());

        $userCheckerEvent->setAsUnauthorized();

        $this->assertFalse($userCheckerEvent->isAuthorized());

        $userCheckerEvent->setAsAuthorized();

        $this->assertTrue($userCheckerEvent->isAuthorized());
    }
}