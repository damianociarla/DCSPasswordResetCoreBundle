<?php

namespace DCS\PasswordReset\CoreBundle\Event;

class ResetRequestCheckerEvent extends ResetRequestEvent
{
    use AuthorizableEvent;
}