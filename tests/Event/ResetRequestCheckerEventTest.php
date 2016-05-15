<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Event\ResetRequestCheckerEvent;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use Symfony\Component\EventDispatcher\Event;

class ResetRequestCheckerEventTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $resetRequest = new ResetRequest();
        $resetRequestCheckerEvent = new ResetRequestCheckerEvent($resetRequest);

        $this->assertInstanceOf(Event::class, $resetRequestCheckerEvent);
        $this->assertEquals($resetRequest, $resetRequestCheckerEvent->getResetRequest());
        $this->assertInstanceOf(ResetRequestInterface::class, $resetRequestCheckerEvent->getResetRequest());
        $this->assertTrue($resetRequestCheckerEvent->isAuthorized());

        $resetRequestCheckerEvent->setAsUnauthorized();

        $this->assertFalse($resetRequestCheckerEvent->isAuthorized());

        $resetRequestCheckerEvent->setAsAuthorized();

        $this->assertTrue($resetRequestCheckerEvent->isAuthorized());
    }
}