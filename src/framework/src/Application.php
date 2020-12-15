<?php

namespace Framework;

use Cekurte\Environment\Environment;
use Cekurte\Environment\Exception\FilterException;
use Exception;
use Framework\Utils\Configuration\ArrayConfiguration;
use Framework\Utils\Configuration\ConfigurationInterface;
use LogicException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use ReflectionObject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use function dirname;

abstract class Application implements ApplicationInterface
{
    /**
     * @var bool
     */
    protected bool $booted = false;

    /**
     * @var string|null
     */
    private ?string $projectDir = null;

    /**
     * @var ContainerInterface|ContainerBuilder|null
     */
    private ?ContainerBuilder $container = null;

    /**
     * @var ArrayConfiguration|ConfigurationInterface|null
     */
    private ?ConfigurationInterface $config;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * Application constructor.
     * @throws FilterException
     */
    public function __construct() {
        $this->config = new ArrayConfiguration(
            Environment::getAll()
        );

        if (!empty($this->config->get('APP_TIMEZONE'))) {
            date_default_timezone_set($this->config->get('APP_TIMEZONE', 'UTC'));
        }
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function run(ConfigurationInterface $input = null)
    {
        if (!$this->booted) {
            !$this->container? $this->preBoot(): null;
        }

        $this->boot();

        return $this->getCommandHandler()->run($input);
    }

    /**
     * Gets a HTTP handler from the container.
     * @throws Exception
     */
    protected function getCommandHandler()
    {
        return $this->container->get('command_handler');
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        if (null === $this->container) {
            $this->preBoot();
        }

        $this->booted = true;
    }

    /**
     * @return ContainerBuilder|null
     * @throws Exception
     */
    private function preBoot(): ?ContainerBuilder
    {
        $this->initializeLogger();

        $this->initializeContainer();

        return $this->container;
    }

    /**
     * Initializes the service container.
     *
     * The built version of the service container is used when fresh, otherwise the
     * container is built.
     * @throws Exception
     */
    protected function initializeContainer()
    {
        $container = null;
        $container = $this->buildContainer();
        $container->compile();
        $this->container = $container;
        $this->container->set('kernel', $this);
    }

    /**
     * Builds the service container.
     *
     * @return ContainerBuilder The compiled service container
     *
     * @throws RuntimeException
     * @throws Exception
     */
    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $container->addObjectResource($this);
        $this->prepareContainer($container);

        return $container;
    }


    /**
     * Gets a new ContainerBuilder instance used to build the service container.
     *
     * @return ContainerBuilder
     */
    protected function getContainerBuilder(): ContainerBuilder
    {
        return new ContainerBuilder();
    }

    /**
     * Prepares the ContainerBuilder before it is compiled.
     *
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        $container = $this->register($container);

        $this->build($container);
    }

    /**
     * The extension point similar to the Bundle::build() method.
     *
     * Use this method to register compiler passes and manipulate the container during the building process.
     *
     * @param ContainerBuilder $container
     */
    protected function build(ContainerBuilder $container)
    {
    }

    /**
     * Initializes the Logger
     */
    protected function initializeLogger()
    {
        $this->logger = new Logger('service_a_logger');
        $this->logger->pushHandler(new StreamHandler('php://stdout'));
    }

    /**
     * @inheritDoc
     */
    public function register(ContainerBuilder $container): ContainerBuilder
    {
        $container->set('configs', $this->getConfig());

        $container->set('logger', $this->getLogger());

        $container->setParameter('broker.url', $this->getConfig()->get("BROKER_URL"));

        return $container;
    }

    /**
     * @inheritDoc
     */
    public function getProjectDir(): string
    {
        if (null === $this->projectDir) {
            $r = new ReflectionObject($this);

            if (!is_file($dir = $r->getFileName())) {
                throw new LogicException(sprintf('Cannot auto-detect project dir for kernel of class "%s".', $r->name));
            }

            $dir = $rootDir = dirname($dir);
            while (!is_file($dir.'/composer.json')) {
                if ($dir === dirname($dir)) {
                    return $this->projectDir = $rootDir;
                }
                $dir = dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ConfigurationInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): array
    {
        return [
            'APP_VERSION' => $this->config->get('APP_VERSION'),
            'BASE_VERSION' => $this->config->get('BASE_VERSION'),
            'OPS_VERSION' => $this->config->get('OPS_VERSION'),
        ];
    }
}