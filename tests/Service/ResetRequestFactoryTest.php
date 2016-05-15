<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Service;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Service\ResetRequestFactory;
use DCS\PasswordReset\CoreBundle\Service\ResetRequestFactoryInterface;
use DCS\PasswordReset\CoreBundle\Service\TokenGenerator\TokenGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Model\UserInterface;

class ResetRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    private static $token = 'helloworld';
    private static $currentDateTime = '2016-01-01 00:00:00';

    private $tokenGenerator;
    private $dateTimeGenerator;

    public function setUp()
    {
        $this->tokenGenerator = $this->getMock(TokenGeneratorInterface::class);
        $this->tokenGenerator->method('generate')->willReturn(self::$token);

        $this->dateTimeGenerator = $this->getMock(DateTimeGeneratorInterface::class);
        $this->dateTimeGenerator->method('generate')->willReturn(\DateTime::createFromFormat('Y-m-d H:i:s', self::$currentDateTime));

    }

    public function testConstructor()
    {
        return new ResetRequestFactory(
            ResetRequest::class,
            $this->tokenGenerator,
            $this->dateTimeGenerator
        );
    }

    /**
     * @depends testConstructor
     */
    public function testCreateFromUserMethod(ResetRequestFactoryInterface $resetRequestFactory)
    {
        $user = $this->getMock(UserInterface::class);
        $resetRequest = $resetRequestFactory->createFromUser($user);
        
        $this->assertInstanceOf(ResetRequestInterface::class, $resetRequest);
        $this->assertEquals($user, $resetRequest->getUser());
        $this->assertEquals(self::$token, $resetRequest->getToken());
        $this->assertEquals(\DateTime::createFromFormat('Y-m-d H:i:s', self::$currentDateTime), $resetRequest->getCreatedAt());
    }
}