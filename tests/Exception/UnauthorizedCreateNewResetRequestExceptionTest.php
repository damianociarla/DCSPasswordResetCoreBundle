<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Event;

use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedCreateNewResetRequestException;
use DCS\User\CoreBundle\Model\UserInterface;

class UnauthorizedCreateNewResetRequestExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndMessage()
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();

        $exception = new UnauthorizedCreateNewResetRequestException($user);

        $this->assertInstanceOf(UserInterface::class, $exception->getUser());
    }
}