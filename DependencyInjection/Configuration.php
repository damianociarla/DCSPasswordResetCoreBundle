<?php

namespace DCS\PasswordReset\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dcs_password_reset_core');

        $rootNode
            ->children()
                ->integerNode('token_ttl')
                    ->defaultValue(86400)
                ->end()
                ->integerNode('waiting_time_new_request')
                    ->defaultValue(86400)
                ->end()
                ->scalarNode('model_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('repository_service')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('services')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('token_generator')
                            ->defaultValue('dcs_password_reset.service.token_generator.random')
                        ->end()
                        ->scalarNode('date_time_generator')
                            ->defaultValue('dcs_password_reset.service.date_time_generator.generic')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}