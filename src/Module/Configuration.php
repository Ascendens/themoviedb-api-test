<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

/**
 * Provides access to <b>configuration</b> API methods
 *
 * @link http://docs.themoviedb.apiary.io/#reference/configuration
 */
class Configuration extends AbstractModule
{
    /**
     * Get the system wide configuration information
     *
     * @param array $headers Custom request headers
     * @param array $options HTTP client options
     * @return array Response body
     */
    public function get(array $headers = [], array $options = [])
    {
        return $this->makeRequest('/configuration', 'GET', '', [], $headers, $options);
    }

    /**
     * @inheritdoc
     */
    protected function getRequiredParametersMap()
    {
        return [
            'common' => 'api_key'
        ];
    }
}
