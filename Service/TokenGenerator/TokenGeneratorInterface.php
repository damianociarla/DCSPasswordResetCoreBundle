<?php

namespace DCS\PasswordReset\CoreBundle\Service\TokenGenerator;

interface TokenGeneratorInterface
{
    /**
     * Generate a new token
     *
     * @return string
     */
    public function generate();
}