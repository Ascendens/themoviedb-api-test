<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

/**
 * Provides access to <b>authentication</b> API methods. Not all methods available
 *
 * @link http://docs.themoviedb.apiary.io/#reference/authentication
 */
class Authentication extends AbstractModule
{
    /**
     * Starts new guest session
     *
     * @link http://docs.themoviedb.apiary.io/#reference/authentication/authenticationguestsessionnew
     *
     * @param array $headers headers
     * @param array $options HTTP client options
     * @return array Response body
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
