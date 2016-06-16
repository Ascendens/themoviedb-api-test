<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use Ascendens\Tmdb\Exception\ModuleNotFoundException;
use InvalidArgumentException;

/**
 * Uses predefined factories to create modules instances
 */
class ModuleFactory implements ModuleFactoryInterface
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var callable[]
     */
    protected $factories;

    /**
     * @var ModuleInterface[]
     */
    private $initialized;

    /**
     * @param string $baseUri
     * @param string $apiKey
     */
    public function __construct($baseUri, $apiKey)
    {
        $this->baseUri = (string) $baseUri;
        $this->apiKey = (string) $apiKey;
    }

    /**
     * Register callable that returns appropriate module instance
     *
     * @param string $codename
     * @param callable $factory
     * @return self
     */
    public function addModuleFactory($codename, callable $factory)
    {
        $this->factories[$codename] = $factory;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function make($codename)
    {
        if (!isset($this->initialized[$codename])) {
            if (!isset($this->factories[$codename])) {
                throw new ModuleNotFoundException(
                    sprintf('Can\'t resolve module for given codename: %s', $codename)
                );
            }
            $module = call_user_func($this->factories[$codename], $this->baseUri, $this->apiKey);
            if (!$module instanceof ModuleInterface) {
                throw new InvalidArgumentException(
                    sprintf('Invalid factory. Codename: %s', $codename)
                );
            }
            $this->initialized[$codename] = $module;
        }

        return $this->initialized[$codename];
    }
}
