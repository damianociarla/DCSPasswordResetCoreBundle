<?php

namespace DCS\PasswordReset\CoreBundle;

class DCSPasswordResetCoreEvents
{
    const BEFORE_CREATE_RESET_REQUEST   = 'dcs_password_reset.core.event.before_create_reset_request';
    const BEFORE_RESET_PASSWORD         = 'dcs_password_reset.core.event.before_reset_password';

    const BEFORE_SAVE_RESET_REQUEST  = 'dcs_password_reset.core.event.before_save_reset_request';
    const SAVE_RESET_REQUEST         = 'dcs_password_reset.core.event.save_reset_request';
    const AFTER_SAVE_RESET_REQUEST   = 'dcs_password_reset.core.event.after_save_reset_request';
}