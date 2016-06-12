<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

class Authentication extends AbstractModule
{
    /**
     * Starts new guest session
     *
     * @param array $headers
     * @param array $options
     * @return array
     */
    public function newGuestSession(array $headers = [], array $options = [])
    {
        return $this->makeRequest('/authentication/guest_session/new', 'GET', '', [], $headers, $options);
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
