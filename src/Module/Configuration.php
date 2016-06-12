<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

class Configuration extends AbstractModule
{
    /**
     * Get the system wide configuration information
     *
     * @param array $headers
     * @param array $options
     * @return array
     */
    public function get(array $headers = [], array $options = [])
    {
        return $this->makeRequest('/configuration', 'GET', '', [], $headers, $options);
    }

    /**
     * @return array
     */
    protected function getRequiredParametersMap()
    {
        return [
            'common' => 'api_key'
        ];
    }
}
