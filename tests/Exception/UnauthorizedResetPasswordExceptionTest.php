<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedResetPasswordException;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

class UnauthorizedResetPasswordExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndMessage()
    {
        $resetRequest = $this->getMockBuilder(ResetRequestInterface::class)->getMock();

        $exception = new UnauthorizedResetPasswordException($resetRequest);

        $this->assertInstanceOf(ResetRequestInterface::class, $exception->getResetRequest());
        $this->assertEquals('Unauthorized reset password', $exception->getMessage());
    }
}