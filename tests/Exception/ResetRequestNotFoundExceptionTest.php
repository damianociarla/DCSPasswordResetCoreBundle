<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Exception\ResetRequestNotFoundException;

class ResetRequestNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndMessage()
    {
        $exception = new ResetRequestNotFoundException('123');

        $this->assertEquals('123', $exception->getToken());
        $this->assertEquals('Reset request not found with the token: 123', $exception->getMessage());
    }
}