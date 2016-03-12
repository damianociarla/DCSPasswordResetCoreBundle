<?php

namespace DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator;

class GenericDateTimeGenerator implements DateTimeGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate()
    {
        return new \DateTime();
    }
}