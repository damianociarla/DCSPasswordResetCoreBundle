[![Build Status](https://travis-ci.org/damianociarla/DCSPasswordResetCoreBundle.svg?branch=master)](https://travis-ci.org/damianociarla/DCSPasswordResetCoreBundle)
[![Coverage Status](https://coveralls.io/repos/github/damianociarla/DCSPasswordResetCoreBundle/badge.svg?branch=master)](https://coveralls.io/github/damianociarla/DCSPasswordResetCoreBundle?branch=master)

# DCSPasswordResetCoreBundle

This bundle provides the tools and the general logic to manage the reset password integrating the bundle [DCSUserCoreBundle](https://github.com/damianociarla/DCSUserCoreBundle). 

It provides the abstraction of operations such as saving and searching for a single reset request through specific events.

Note: This bundle does not provide the final implementation of the operations but you must add and configure libraries (or create your own) to ensure the execution of commands.

## Installation

### Prerequisites

This version of the bundle requires Symfony 2.8+.

You must also install and configure the bundle [DCSUserCoreBundle](https://github.com/damianociarla/DCSUserCoreBundle).

### Require the bundle

Run the following command:

	$ composer require dcs/password-reset-core-bundle "~1.0@dev"

Composer will install the bundle to your project's `vendor/dcs/password-reset-core-bundle` directory.

### Enable the bundle

Enable the bundle in the kernel:

	<?php
	// app/AppKernel.php

	public function registerBundles()
	{
		$bundles = array(
			// ...
			new DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreBundle(),
			// ...
		);
	}
	
### Create your ResetRequest class

Until now he has never talked about **persistence**. This because? Why DCSPasswordResetCoreBundle not know the logic of how they will be implemented the Save methods. It is allowed total freedom of implementation.

### Configure

Now that you have properly enabled this bundle, the next step is to configure it to work with the specific needs of your application.

Add the following configuration to your `config.yml`.

	dcs_password_reset_core:
        model_class: Your\ResetRequest\Class
        repository_service: your_repository_service

### Complete configuration

The complete configuration includes other parameters that you can change:

    dcs_password_reset_core:
        # mandatory parameters
        model_class: Your\ResetRequest\Class
        repository_service: your_repository_service
        
        # optional parameters
        waiting_time_new_request: 86400  # waiting time before a new request
        token_ttl: 86400                 # life time of the token
        services:
            token_generator: dcs_password_reset.service.token_generator.random            # service to generate the token
            date_time_generator: dcs_password_reset.service.date_time_generator.generic   # service to generate the date

## The DCSPasswordReset bundles

They were made available two bundles to install:

[DCSPasswordResetPersistenceORMBundle](https://github.com/damianociarla/DCSPasswordResetPersistenceORMBundle) This bundle provides the final implementation of the persistence through DoctrineORM.

[DCSPasswordResetExplainViewBundle](https://github.com/damianociarla/DCSPasswordResetExplainViewBundle) This bundle provides the settings to display the password recovery services through view and form.

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/damianociarla/DCSPasswordResetCoreBundle/issues).