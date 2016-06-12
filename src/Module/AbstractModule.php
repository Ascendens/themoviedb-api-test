<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

use Ascendens\Tmdb\Http\ClientInterface;
use Ascendens\Tmdb\Module\Validator\ParametersValidatorInterface;
use Ascendens\Tmdb\Module\Validator\Parameters;
use Zend\Diactoros\Request;
use Ascendens\Tmdb\Http\Request\Serializer as RequestSerializer;
use Ascendens\Tmdb\Http\Response\Serializer as ResponseSerializer;
use LogicException;
use InvalidArgumentException;

abstract class AbstractModule implements ModuleInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var ParametersValidatorInterface
     */
    protected $parametersValidator;

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param ClientInterface $httpClient
     * @param ParametersValidatorInterface $parametersValidator
     */
    public function __construct(ClientInterface $httpClient, ParametersValidatorInterface $parametersValidator = null)
    {
        $this->httpClient = $httpClient;
        $this->parametersValidator = $parametersValidator ?: new Parameters($this->getRequiredParametersMap());
    }

    /**
     * @inheritdoc
     */
    public function setBaseUri($url)
    {
        $this->baseUri = (string) $url;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @inheritdoc
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = (string) $apiKey;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Makes HTTP request via HTTP client and returns decoded body of response
     *
     * @param string $uri
     * @param string $method
     * @param mixed $body
     * @param array $parameters
     * @param array $headers
     * @param array $options
     * @return mixed
     */
    protected function makeRequest(
        $uri,
        $method,
        $body = '',
        array $parameters = [],
        array $headers = [],
        array $options = []
    ) {
        $parameters = $this->appendApiKeyParameter($parameters);
        $this->validateParameters($uri, $parameters);
        $uri = sprintf(
            '%s%s?%s',
            $this->baseUri,
            $uri,
            http_build_query($parameters)
        );

        return ResponseSerializer::getDecodedJsonBody(
            $this->httpClient->send(
                RequestSerializer::with($uri, $method, $body, $headers),
                $options
            )
        );
    }

    /**
     * Returns lists of required parameters.<br>
     * Define in "common" section URI independent parameters.<br>
     * Define sections with full URI name for URI specific parameters.
     *
     * @return array
     */
    abstract protected function getRequiredParametersMap();

    /**
     * @param array $parameters
     * @return array
     * @throws LogicException If no API key found
     */
    private function appendApiKeyParameter(array $parameters)
    {
        $apiKey = isset($parameters['api_key']) ? $parameters['api_key'] : $this->apiKey;
        if (empty($apiKey)) {
            throw new LogicException("API key is not set");
        }
        $parameters['api_key'] = $apiKey;

        return $parameters;
    }

    /**
     * Checks existence of all, common and specific, parameters in request
     *
     * @param string $uri
     * @param array $parameters
     * @throws InvalidArgumentException
     */
    private function validateParameters($uri, array $parameters)
    {
        if (!$this->parametersValidator->isValid($uri, $parameters)) {
            throw new InvalidArgumentException($this->parametersValidator->getLastError());
        }
    }
}
