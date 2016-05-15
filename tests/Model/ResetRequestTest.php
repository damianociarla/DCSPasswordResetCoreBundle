<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Model;

use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Model\UserInterface;

class ResetRequestTest extends \PHPUnit_Framework_TestCase
{
    private $user;

    protected function setUp()
    {
        $this->user = $this->getMock(UserInterface::class);
    }

    public function testConstructor()
    {
        $token = 'abc';
        $createdAt = new \DateTime();

        $resetRequest = new ResetRequest($this->user, $token, $createdAt);

        $this->assertInstanceOf(UserInterface::class, $resetRequest->getUser());
        $this->assertEquals($token, $resetRequest->getToken());
        $this->assertInstanceOf(\DateTime::class, $resetRequest->getCreatedAt());
        $this->assertEquals($createdAt, $resetRequest->getCreatedAt());
    }

    public function testSetterAndGetter()
    {
        $token = 'abc';
        $createdAt = new \DateTime();
        $usedAt = new \DateTime();

        $resetRequest = new ResetRequest();

        $this->assertNull($resetRequest->getUser());
        $this->assertNull($resetRequest->getToken());
        $this->assertNull($resetRequest->getCreatedAt());
        $this->assertNull($resetRequest->getUsedAt());

        $resetRequest->setId(1);
        $resetRequest->setUser($this->user);
        $resetRequest->setToken($token);
        $resetRequest->setCreatedAt($createdAt);
        $resetRequest->setUsedAt($usedAt);

        $this->assertInstanceOf(UserInterface::class, $resetRequest->getUser());
        $this->assertEquals(1, $resetRequest->getId());
        $this->assertEquals($token, $resetRequest->getToken());
        $this->assertInstanceOf(\DateTime::class, $resetRequest->getCreatedAt());
        $this->assertEquals($createdAt, $resetRequest->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $resetRequest->getUsedAt());
        $this->assertEquals($createdAt, $resetRequest->getUsedAt());
    }
}