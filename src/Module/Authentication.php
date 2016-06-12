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
     * @return array
     */
    public function newGuestSession()
    {
        return $this->makeRequest('/authentication/guest_session/new', 'GET');
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
