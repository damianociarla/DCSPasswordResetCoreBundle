<?php

namespace DCS\PasswordReset\CoreBundle\Service\TokenGenerator;

class RandomGenerator implements TokenGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}