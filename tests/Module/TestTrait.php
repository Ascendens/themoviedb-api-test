<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\ModuleInterface;
use PHPUnit_Framework_TestCase;
use Ascendens\Tmdb\Http\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Closure;
use Zend\Diactoros\Response\JsonResponse;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

trait TestTrait
{
    private $moduleClassName = null;

    /**
     * @var array
     */
    private $responses = [];

    /**
     * @return ClientInterface
     */
    private function getHttpClient()
    {
        /**
         * @var PHPUnit_Framework_TestCase $this
         */
        $httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $callback = Closure::bind(function (RequestInterface $request) {
            return $this->processRequest($request);
        }, $this);
        $httpClient->method('send')->willReturnCallback($callback);

        return $httpClient;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws InvalidArgumentException If data not found
     */
    private function processRequest(RequestInterface $request)
    {
        $uri = (string) $request->getUri();
        $method = $request->getMethod();
        if (!isset($this->responses[$uri][$method])) {
            throw new InvalidArgumentException(
                sprintf('404 Not Found for "%s" URI with method "%s"', $uri, $method)
            );
        }

        return new JsonResponse($this->responses[$uri][$method]);
    }

    /**
     * @param string $baseUri
     * @return ModuleInterface
     */
    private function getModule($baseUri = '')
    {
        /**
         * @var ModuleInterface $module
         */
        $module = new $this->moduleClassName($this->getHttpClient());
        $module
            ->setBaseUri($baseUri)
            ->setApiKey($this->apiKey)
        ;

        return $module;
    }
}
