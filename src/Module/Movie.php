<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use Ascendens\Tmdb\Session\SessionAwareInterface;
use Ascendens\Tmdb\Session\SessionAwareTrait;

class Movie extends AbstractModule implements SessionAwareInterface
{
    use SessionAwareTrait;

    /**
     * Returns information of a movie
     *
     * @param int $id
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return array
     */
    public function get($id, array $parameters = [], array $headers = [], array $options = [])
    {
        return $this->makeRequest(sprintf('/movie/%d', $id), 'GET', '', $parameters, $headers, $options);
    }

    /**
     * Sets up rating for movie
     *
     * @param int $id
     * @param int $rating
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return array
     */
    public function setRating($id, $rating, array $parameters = [], array $headers = [], array $options = [])
    {
        return $this->makeRequest(
            sprintf('/movie/%d/rating', (int) $id),
            'POST',
            ['value' => (int) $rating],
            $this->appendSessionId($parameters),
            array_merge($headers, ['Content-Type' => 'application/json']),
            $options
        );
    }

    /**
     * Deletes rating of a movie
     *
     * @param int $id
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return array
     */
    public function deleteRating($id, array $parameters = [], array $headers = [], array $options = [])
    {
        return $this->makeRequest(
            sprintf('/movie/%d/rating', (int) $id),
            'DELETE',
            '',
            $this->appendSessionId($parameters),
            $headers,
            $options
        );
    }

    /**
     * @return array
     */
    protected function getRequiredParametersMap()
    {
        return [
            'common' => 'api_key',
            '/movie/\d+/rating' => 'session_id || guest_session_id'
        ];
    }

    /**
     * @param array $parameters
     * @return array
     */
    private function appendSessionId(array $parameters)
    {
        if (isset($parameters['session_id']) || isset($parameters['guest_session_id'])) {
            return $parameters;
        }
        if ($sid = $this->getSessionId()) {
            $parameters['session_id'] = $sid;
        } elseif ($sid = $this->getGuestSessionId()) {
            $parameters['guest_session_id'] = $sid;
        }

        return $parameters;
    }
}
