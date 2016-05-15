<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Service\TokenGenerator;

use DCS\PasswordReset\CoreBundle\Service\TokenGenerator\RandomGenerator;

class RandomGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateMethod()
    {
        $tokenGenerator = new RandomGenerator();
        $this->assertTrue(is_string($tokenGenerator->generate()));
    }
}