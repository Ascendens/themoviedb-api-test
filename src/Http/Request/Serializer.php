<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Http\Request;

use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;
use Zend\Diactoros\Stream;

/**
 * Serializer for requests to TMDB API
 *
 * @copyright Andrey Kotelnik <uarockfan@gmail.com>
 */
final class Serializer
{
    /**
     * Creates request with JSON encoded body
     *
     * @param string $uri
     * @param string $method
     * @param mixed $message
     * @param array $headers
     * @return RequestInterface
     */
    public static function with($uri, $method, $message = '', array $headers = [])
    {
        $body = new Stream('php://temp', 'wb+');
        if (!empty($message)) {
            $body->write(json_encode($message));
            $body->rewind();
        }

        return new Request($uri, $method, $body, $headers);
    }
}
