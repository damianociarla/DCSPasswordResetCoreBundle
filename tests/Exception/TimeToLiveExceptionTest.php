<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Exception\TimeToLiveException;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class TimeToLiveExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndMessage()
    {
        $resetRequest = $this->getMockBuilder(ResetRequestInterface::class)->getMock();

        $exception = new TimeToLiveException($resetRequest);

        $this->assertInstanceOf(ResetRequestInterface::class, $exception->getResetRequest());
    }
}