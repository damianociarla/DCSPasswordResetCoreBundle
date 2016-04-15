<?php

namespace DCS\PasswordReset\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DCSPasswordResetCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('dcs_password_reset.token_ttl', $config['token_ttl']);
        $container->setParameter('dcs_password_reset.waiting_time_new_request', $config['waiting_time_new_request']);
        $container->setParameter('dcs_password_reset.model_class', $config['model_class']);
        $container->setParameter('dcs_password_reset.repository_service', $config['repository_service']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('service.xml');
        $container->setAliases([
            'dcs_password_reset.service.date_time_generator' => $config['services']['date_time_generator'],
            'dcs_password_reset.service.token_generator' => $config['services']['token_generator'],
        ]);

        $loader->load('checker.xml');
        $loader->load('handler.xml');
        $loader->load('manager.xml');
    }
}