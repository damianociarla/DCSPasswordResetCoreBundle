<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Service\TokenGenerator;

use DCS\PasswordReset\CoreBundle\Service\TokenGenerator\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

class UuidGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateMethod()
    {
        $tokenGenerator = new UuidGenerator();
        $this->assertInstanceOf(UuidInterface::class, $tokenGenerator->generate());
    }
}