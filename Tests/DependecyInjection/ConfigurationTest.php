<?php

namespace Nicolassing\SequenceBundle\Tests\DependencyInjection;

use Nicolassing\SequenceBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testProcessSimpleCase()
    {
        $configs = array(
            array(
                'sequence_class' => 'Foobar',
                'handlers' => array('test' => array('type' => 'default', 'length' => 9, 'prefix' => 'T'))
            )
        );

        $config = $this->process($configs);

        $this->assertArrayHasKey('handlers', $config);
        $this->assertArrayHasKey('test', $config['handlers']);
        $this->assertEquals('default', $config['handlers']['test']['type']);
        $this->assertEquals(9, $config['handlers']['test']['length']);
        $this->assertEquals('T', $config['handlers']['test']['prefix']);
    }

    /**
     * Processes an array of configurations and returns a compiled version.
     *
     * @param array $configs An array of raw configurations
     *
     * @return array A normalized array
     */
    protected function process($configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), $configs);
    }
}
