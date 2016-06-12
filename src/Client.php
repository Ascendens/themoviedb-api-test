<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb;

use Ascendens\Tmdb\Http\ClientInterface as HttpClientInterface;
use Ascendens\Tmdb\Module\ModuleFactory;
use Ascendens\Tmdb\Module\ModuleFactoryInterface;
use Ascendens\Tmdb\Exception\ModuleNotFoundException;
use Ascendens\Tmdb\Module\ModuleInterface;
use Ascendens\Tmdb\Session\SessionAwareInterface;
use Ascendens\Tmdb\Session\SessionAwareTrait;
use Closure;
use InvalidArgumentException;

/**
 * Provide dynamic access to existent modules
 */
class Client implements SessionAwareInterface
{
    use SessionAwareTrait;

    const API_URL = 'http://api.themoviedb.org/3';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var ModuleFactory
     */
    protected $moduleFactory;

    /**
     * @param string $apiKey
     * @param HttpClientInterface $httpClient
     */
    public function __construct($apiKey, HttpClientInterface $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
        $this->moduleFactory = new ModuleFactory(self::API_URL, $apiKey);
    }

    /**
     * @return ModuleFactoryInterface
     */
    public function getModuleFactory()
    {
        return $this->moduleFactory;
    }
    
    /**
     * Tries dynamically create module and returns it
     *
     * @param string $name
     * @return ModuleInterface
     * @InvalidArgumentException If expected class not found
     */
    public function __get($name)
    {
        try {
            return $this->moduleFactory->make($name);
        } catch (ModuleNotFoundException $e) {
            $expectedClass = sprintf('%s\\Module\\%s', __NAMESPACE__, ucfirst($name));
            if (!class_exists($expectedClass)) {
                throw new InvalidArgumentException(
                    sprintf('Appropriate module not found: %s. Expected class: %s', $name, $expectedClass)
                );
            }
            $closure = Closure::bind(function () use ($expectedClass) {
                /**
                 * @var ModuleInterface $module
                 */
                $module = new $expectedClass($this->httpClient);
                return $module
                    ->setBaseUri(self::API_URL)
                    ->setApiKey($this->apiKey)
                ;
            }, $this);
            $this->moduleFactory->addModuleFactory($name, $closure);
        } finally {
            $module = $this->moduleFactory->make($name);
            if ($module instanceof SessionAwareInterface) {
                /**
                 * @var SessionAwareInterface $module
                 */
                $module->setSessionId($this->getSessionId());
                $module->setGuestSessionId($this->getGuestSessionId());
            }

            return $module;
        }
    }
}
