<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use Ascendens\Tmdb\Session\SessionAwareInterface;
use Ascendens\Tmdb\Session\SessionAwareTrait;

/**
 * Provides access to <b>movies</b> API methods. Not all methods available
 *
 * @link http://docs.themoviedb.apiary.io/#reference/movies
 */
class Movie extends AbstractModule implements SessionAwareInterface
{
    use SessionAwareTrait;

    /**
     * Returns information of a movie
     *
     * @link http://docs.themoviedb.apiary.io/#reference/movies/movieid
     *
     * @param int $id
     * @param array $parameters API parameters
     * @param array $headers Request headers
     * @param array $options HTTP client options
     * @return array Response body
     */
    public function get($id, array $parameters = [], array $headers = [], array $options = [])
    {
        return $this->makeRequest(sprintf('/movie/%d', $id), 'GET', '', $parameters, $headers, $options);
    }

    /**
     * Sets up rating for movie
     *
     * @link http://docs.themoviedb.apiary.io/#reference/movies/movieidrating/post
     *
     * @param int $id
     * @param int $rating
     * @param array $parameters API parameters
     * @param array $headers Request headers
     * @param array $options HTTP client options
     * @return array Response body
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
     * @link http://docs.themoviedb.apiary.io/#reference/movies/movieidrating/delete
     *
     * @param int $id
     * @param array $parameters API parameters
     * @param array $headers Request headers
     * @param array $options HTTP client options
     * @return array Response body
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
     * @inheritdoc
     */
    protected function getRequiredParametersMap()
    {
        return [
            'common' => 'api_key',
            '/movie/\d+/rating' => 'session_id || guest_session_id'
        ];
    }

    /**
     * Adds session ID to parameters if necessary.
     *
     * @param array $parameters Current parameters
     * @return array Parameters with session ID
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
