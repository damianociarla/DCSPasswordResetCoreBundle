<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Event\ResetRequestEvent;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use Symfony\Component\EventDispatcher\Event;

class ResetRequestEventTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $resetRequest = new ResetRequest();
        $resetRequestEvent = new ResetRequestEvent($resetRequest);

        $this->assertInstanceOf(Event::class, $resetRequestEvent);
        $this->assertEquals($resetRequest, $resetRequestEvent->getResetRequest());
        $this->assertInstanceOf(ResetRequestInterface::class, $resetRequestEvent->getResetRequest());
    }
}