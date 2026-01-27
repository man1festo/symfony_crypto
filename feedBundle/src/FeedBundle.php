<?php

namespace FeedBundle;

use FeedBundle\Domain\DTO\UpdateFeedDTO;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use FeedBundle\Controller\Amqp\UpdateFeed\Consumer;

class FeedBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');
    }
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig(
            'doctrine',
            [
                'orm' => [
                    'mappings' => [
                        'FeedBundle' => [
                            'type' => 'attribute',
                            'dir' => '%kernel.project_dir%/feedBundle/src/Domain/Entity',
                            'prefix' => 'FeedBundle\Domain\Entity',
                            'alias' => 'FeedBundle'
                        ]
                    ]
                ]
            ]
        );
//        $builder->prependExtensionConfig(
//            'old_sound_rabbit_mq',
//            [
//                'consumers' => $this->makeUpdateFeedConsumerDefinition()
//            ]
//        );
        $builder->prependExtensionConfig(
            'framework',
            [
                'messenger' => [
                    'transports' => [
                        'update_feed' => [
                            'dsn' => '%env(MESSENGER_AMQP_UPDATE_FEED_TRANSPORT_DSN)%',
                            'options' => [
                                'exchange' => ['name' => 'old_sound_rabbit_mq.update_feed', 'type' => 'direct'],
                            ],
                            'serializer' => 'messenger.transport.symfony_serializer',
                        ],
                    ],
                    'routing' => [
                        UpdateFeedDTO::class => 'update_feed',
                    ]
                ],
            ],
        );
    }

    private function makeUpdateFeedConsumerDefinition(): array
    {
        return [
            "update_feed" => [
                'connection' => 'default',
                'exchange_options' => ['name' => 'old_sound_rabbit_mq.update_feed', 'type' => 'direct'],
                'queue_options' => [
                    'name' => "old_sound_rabbit_mq.consumer.update_feed"
                ],
                'callback' => Consumer::class,
                'idle_timeout' => 300,
                'idle_timeout_exit_code' => 0,
                'graceful_max_execution' => ['timeout' => 1800, 'exit_code' => 0],
                'qos_options' => ['prefetch_size' => 0, 'prefetch_count' => 1, 'global' => false],
            ]
        ];
    }
}
