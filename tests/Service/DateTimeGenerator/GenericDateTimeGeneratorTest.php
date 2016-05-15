<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Service\DateTimeGenerator;

use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\GenericDateTimeGenerator;

class GenericDateTimeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateMethod()
    {
        $dateTimeGenerator = new GenericDateTimeGenerator();
        $this->assertInstanceOf(\DateTime::class, $dateTimeGenerator->generate());
    }
}