<?php

namespace DCS\PasswordReset\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class RepositoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $repositoryService = $container->getParameter('dcs_password_reset.repository_service');

        if (!$container->has($repositoryService)) {
            throw new ServiceNotFoundException($repositoryService);
        }

        $container->setAlias('dcs_password_reset.repository', $repositoryService);
    }
}
