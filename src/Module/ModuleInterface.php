<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

interface ModuleInterface
{
    /**
     * Sets up base API URI
     *
     * @param string $url
     * @return ModuleInterface
     */
    public function setBaseUri($url);

    /**
     * Returns base API URI
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * @param string $apiKey
     * @return ModuleInterface
     */
    public function setApiKey($apiKey);

    /**
     * @return string
     */
    public function getApiKey();
}
