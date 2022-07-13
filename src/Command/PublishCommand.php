<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Command;

use Psr\Container\ContainerInterface;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Utils\Filesystem\Filesystem;

/**
 * @Command
 */
class PublishCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->fileSystem = $container->get(Filesystem::class);

        parent::__construct('admin:publish');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Publish config files');
    }

    public function handle()
    {
        $this->publish();

        $this->info('Done.');
    }

    /**
     * Publish resource
     */
    public function publish()
    {
        $this->call('vendor:publish', ['package' => 'oyhdd/hyperf-admin']);
        $this->call('vendor:publish', ['package' => 'hyperf/session']);
        $this->call('vendor:publish', ['package' => 'hyperf/view-engine']);
        $this->call('vendor:publish', ['package' => 'hyperf/translation']);
        $this->call('vendor:publish', ['package' => 'hyperf-ext/hashing']);

        // Publish assets files
        $this->fileSystem->copyDirectory(
            dirname(dirname(__DIR__)) . '/resource/assets',
            BASE_PATH . '/public/vendor/hyperf-admin'
        );

        // Publish language files
        $this->fileSystem->copyDirectory(
            dirname(dirname(__DIR__)) . '/resource/languages',
            config('translation.path', BASE_PATH . '/storage/languages')
        );

        // Publish view files
        $this->fileSystem->copyDirectory(
            dirname(dirname(__DIR__)) . '/resource/view',
            BASE_PATH . '/storage/view/'
        );
    }
}
