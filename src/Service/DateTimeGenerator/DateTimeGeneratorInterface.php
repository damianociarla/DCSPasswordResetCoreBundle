<?php

namespace DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator;

interface DateTimeGeneratorInterface
{
    /**
     * Generate new current DateTime
     *
     * @return \DateTime
     */
    public function generate();
}