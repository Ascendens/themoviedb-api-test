<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use InvalidArgumentException;

/**
 * Provides access to <b>discover</b> API methods
 *
 * @link http://docs.themoviedb.apiary.io/#reference/discover
 */
class Discover extends AbstractModule
{
    /**
     * Makes discover movie request
     *
     * @link http://docs.themoviedb.apiary.io/#reference/discover/discovermovie
     * 
     * @param array $parameters API parameters
     * @param array $headers Request headers
     * @param array $options HTTP client options
     * @return array Response body
     */
    public function movie(array $parameters = [], array $headers = [], array $options = [])
    {
        if (isset($parameters['certification_country'])
            && !isset($parameters['certification'])
            && !isset($parameters['certification.lte'])
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'When specified "%s", "%s" or "%s" must specified too',
                    'certification_country',
                    'certification',
                    'certification.lte'
                )
            );
        }

        return $this->makeRequest('/discover/movie', 'GET', '', $parameters, $headers, $options);
    }

    /**
     * Makes discover tv request
     *
     * @link http://docs.themoviedb.apiary.io/#reference/discover/discovertv
     * 
     * @param array $parameters API parameters
     * @param array $headers Request headers
     * @param array $options HTTP client options
     * @return array Response body
     */
    public function tv(array $parameters = [], array $headers = [], array $options = [])
    {
        return $this->makeRequest('/discover/tv', 'GET', '', $parameters, $headers, $options);
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
