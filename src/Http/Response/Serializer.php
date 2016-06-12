<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Http\Response;

use Psr\Http\Message\ResponseInterface;
use InvalidArgumentException;

/**
 * Serializer for responses from TMDB API
 */
final class Serializer
{
    /**
     * Returns JSON decoded body of response
     *
     * @param ResponseInterface $response
     * @return mixed Decoded body
     */
    public static function getDecodedJsonBody(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        if (false === strpos($contentType, 'application/json')) {
            throw new InvalidArgumentException(
                sprintf('Incorrect content type: %s. Requires "application/json"', $contentType)
            );
        }

        return json_decode((string) $response->getBody(), true);
    }
}
