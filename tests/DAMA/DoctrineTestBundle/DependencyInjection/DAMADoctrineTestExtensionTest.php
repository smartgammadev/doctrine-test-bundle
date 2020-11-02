<?php

namespace Tests\DAMA\DoctrineTestBundle\DependencyInjection;

use DAMA\DoctrineTestBundle\DependencyInjection\DAMADoctrineTestExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DAMADoctrineTestExtensionTest extends TestCase
{
    /**
     * @dataProvider loadDataProvider
     */
    public function testLoad(array $configs, array $expectedProcessedConfig): void
    {
        $extension = new DAMADoctrineTestExtension();
        /** @var ContainerBuilder|MockObject $containerBuilder */
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $extension->load($configs, $containerBuilder);

        $this->assertEquals($extension->getProcessedConfig(), $expectedProcessedConfig);
    }

    public function loadDataProvider(): array
    {
        return [
            [[], [
                'enable_static_connection' => true,
                'enable_static_meta_data_cache' => true,
                'enable_static_query_cache' => true,
                'default_keep_static_connection' => false,
            ]],
            [
                [
                    [
                        'enable_static_connection' => false,
                        'default_keep_static_connection' => true,
                    ],
                    [
                        'enable_static_meta_data_cache' => false,
                        'enable_static_query_cache' => false,
                    ],
                ],
                [
                    'enable_static_connection' => false,
                    'enable_static_meta_data_cache' => false,
                    'enable_static_query_cache' => false,
                    'default_keep_static_connection' => true,
                ],
            ],
            [[
                [
                    'enable_static_connection' => [
                        'a' => true,
                        'b' => false,
                    ],
                ],
            ], [
                'enable_static_connection' => [
                    'a' => true,
                    'b' => false,
                ],
                'enable_static_meta_data_cache' => true,
                'enable_static_query_cache' => true,
                'default_keep_static_connection' => false,
            ]],
        ];
    }
}
