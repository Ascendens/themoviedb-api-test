<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use InvalidArgumentException;

class Discover extends AbstractModule
{
    /**
     * Makes discover movie request
     *
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return array
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
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return array
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
