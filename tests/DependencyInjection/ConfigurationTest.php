<?php

namespace DCS\PasswordReset\CoreBundle\Tests\DependencyInjection;

use DCS\PasswordReset\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\IntegerNode;
use Symfony\Component\Config\Definition\ScalarNode;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    protected function setUp()
    {
        $this->configuration = new Configuration();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(ConfigurationInterface::class, $this->configuration);
    }

    public function testGetConfigTreeBuilder()
    {
        $this->assertInstanceOf(TreeBuilder::class, $this->configuration->getConfigTreeBuilder());
    }

    public function testRootNodeNameBuilder()
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        $this->assertEquals('dcs_password_reset_core', $treeBuilder->buildTree()->getName());
    }

    public function testAllNodes()
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();

        /** @var \Symfony\Component\Config\Definition\ArrayNode $tree */
        $tree = $treeBuilder->buildTree();

        $this->assertCount(5, $children = $tree->getChildren());

        $this->assertArrayHasKey('token_ttl', $children);
        $this->assertInstanceOf(IntegerNode::class, $children['token_ttl']);
        $this->assertEquals(86400, $children['token_ttl']->getDefaultValue());

        $this->assertArrayHasKey('waiting_time_new_request', $children);
        $this->assertInstanceOf(IntegerNode::class, $children['waiting_time_new_request']);
        $this->assertEquals(86400, $children['waiting_time_new_request']->getDefaultValue());

        $this->assertArrayHasKey('model_class', $children);
        $this->assertInstanceOf(ScalarNode::class, $children['model_class']);
        $this->assertTrue($children['model_class']->isRequired());

        $this->assertArrayHasKey('services', $children);
        $this->assertInstanceOf(ArrayNode::class, $children['services']);

        $this->assertCount(2, $servicesChildren = $children['services']->getChildren());

        $this->assertArrayHasKey('token_generator', $servicesChildren);
        $this->assertInstanceOf(ScalarNode::class, $servicesChildren['token_generator']);
        $this->assertEquals('dcs_password_reset.service.token_generator.random', $servicesChildren['token_generator']->getDefaultValue());

        $this->assertArrayHasKey('date_time_generator', $servicesChildren);
        $this->assertInstanceOf(ScalarNode::class, $servicesChildren['date_time_generator']);
        $this->assertEquals('dcs_password_reset.service.date_time_generator.generic', $servicesChildren['date_time_generator']->getDefaultValue());
    }
}