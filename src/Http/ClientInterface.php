<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * Makes HTTP request
     *
     * @param RequestInterface $request
     * @param array $options Custom options
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, array $options = []);
}
