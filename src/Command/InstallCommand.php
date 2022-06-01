<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use Hyperf\Utils\Filesystem\Filesystem;

/**
 * @Command
 */
class InstallCommand extends HyperfCommand
{
    /**
     * hyperf-admin install directory
     */
    protected $directory;

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
        $this->directory = config('admin.directory', 'app/Admin');

        parent::__construct('admin:install');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Install the admin package');
    }

    public function handle()
    {
        $this->initAdminDirectory();

        $this->publish();

        $this->initDatabase();

        $this->info('Done.');
    }

    /**
     * Publish resource
     */
    public function publish()
    {
        $this->call('vendor:publish', ['package' => 'hyperf/session']);
        $this->call('vendor:publish', ['package' => 'hyperf/view-engine']);
        $this->call('vendor:publish', ['package' => 'hyperf/translation']);

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

        // Publish route file of Hyperf Admin
        $this->fileSystem->copy(
            dirname(dirname(__DIR__)) . '/publish/routes.php',
            BASE_PATH . '/' . $this->directory . '/routes.php'
        );

        // Append route of Hyperf Admin into config/routes.php
        $routeContent = "\nrequire_once BASE_PATH . '/app/Admin/routes.php';\n";
        if (strpos($this->fileSystem->get(BASE_PATH . '/config/routes.php'), $routeContent) === false) {
            $this->fileSystem->append(
                BASE_PATH . '/config/routes.php',
                sprintf("\n/**\n * This file is part of Hyperf Admin.\n*/%s", $routeContent)
            );
        }
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate', ['--path' => './vendor/oyhdd/hyperf-admin/database/migrations/']);
        $userModel = config('admin.database.user_model');

        if ($userModel::count() == 0) {
            $this->call('db:seed', ['--path' => './vendor/oyhdd/hyperf-admin/database/seeders/']);
        }
    }

    /**
     * Initialize the Admin directory.
     *
     * @return void
     */
    public function initAdminDirectory()
    {
        if (is_dir($this->directory)) {
            $this->warn("{$this->directory} directory already exists !");
            return;
        }

        $this->makeDir("/");
        $this->line('<info>Admin directory was created:</info> ' . $this->directory);
        $this->makeDir('Controller');

        $this->createHomeController();
    }


    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createHomeController()
    {
        $homeController = $this->directory . '/Controller/HomeController.php';
        $contents = $this->getStub('HomeController');

        $this->fileSystem->put(
            $homeController,
            str_replace(
                ['%NAMESPACE%'],
                [$this->namespace('Controller')],
                $contents
            )
        );

        $this->line('<info>HomeController file was created:</info> ' . $homeController);
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->fileSystem->get(__DIR__ . "/stubs/{$name}.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->fileSystem->makeDirectory("{$this->directory}/{$path}", 0755, true, true);
    }

    /**
     * Get namespace of Controller
     *
     * @param string $name
     *
     * @return string
     */
    protected function namespace($name = null): string
    {
        $base = str_replace('\\Controller', '\\', config('admin.route.namespace'));
        return trim($base, '\\') . ($name ? "\\{$name}" : '');
    }
}
