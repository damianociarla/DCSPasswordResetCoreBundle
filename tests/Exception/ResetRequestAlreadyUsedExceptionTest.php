<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Exception\ResetRequestAlreadyUsedException;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class ResetRequestAlreadyUsedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndMessage()
    {
        $resetRequest = $this->getMockBuilder(ResetRequestInterface::class)->getMock();
        $resetRequest->expects($this->once())->method('getToken')->willReturn('123');

        $exception = new ResetRequestAlreadyUsedException($resetRequest);

        $this->assertInstanceOf(ResetRequestInterface::class, $exception->getResetRequest());
        $this->assertEquals('The request with the token "123" is already used', $exception->getMessage());
    }
}