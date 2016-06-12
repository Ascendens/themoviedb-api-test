<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\AbstractModule;
use Ascendens\Tmdb\Module\Validator\Parameters;
use ReflectionMethod;
use LogicException;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /**
     * @var string
     */
    private $apiKey = 'some_key';

    public function testCorrectGetRequest()
    {
        $uri = array_keys($this->responses)[0];
        $method = 'GET';
        parse_str(parse_url($uri, PHP_URL_QUERY), $params);
        $module = $this->getModule();
        $result = $this->callNonPublicMethod($module, 'makeRequest', [
            parse_url($uri, PHP_URL_PATH),
            $method,
            '',
            $params
        ]);
        $this->assertEquals($this->responses[$uri][$method], $result);
    }

    public function testCorrectPostRequest()
    {
        $uri = array_keys($this->responses)[0];
        $method = 'POST';
        parse_str(parse_url($uri, PHP_URL_QUERY), $params);
        $module = $this->getModule();
        $result = $this->callNonPublicMethod($module, 'makeRequest', [
            parse_url($uri, PHP_URL_PATH),
            $method,
            '',
            $params
        ]);
        $this->assertEquals($this->responses[$uri][$method], $result);
    }

    public function testAnotherExistingPath()
    {
        $uri = '/path-2';
        $method = 'GET';
        $module = $this->getModule();
        $result = $this->callNonPublicMethod($module, 'makeRequest', [$uri, $method]);
        $this->assertEquals($this->responses[sprintf('%s?api_key=%s', $uri, $this->apiKey)][$method], $result);
    }

    /**
     * @expectedException LogicException
     */
    public function testMissingRequiredParameter()
    {
        $module = $this->getModule();
        $this->callNonPublicMethod($module, 'makeRequest', ['/path-1', 'GET', '', [
            'param_1' => 'one'
        ]]);
    }

    /**
     * @expectedException LogicException
     */
    public function testMissingApiKey()
    {
        $module = $this->getModule();
        $module->setApiKey(null);
        $this->callNonPublicMethod($module, 'makeRequest', ['/path-2', 'GET', '', [
            'param_1' => 'one'
        ]]);
    }

    public function testApiKeyAsParameter()
    {
        $uri = '/path-2';
        $method = 'GET';
        $someAnotherKey = 'some_another_key';
        $module = $this->getModule()->setApiKey(null);
        $result = $this->callNonPublicMethod(
            $module,
            'makeRequest',
            [$uri, $method, '', ['api_key' => $someAnotherKey]]
        );
        $this->assertEquals($this->responses[sprintf('%s?api_key=%s', $uri, $someAnotherKey)][$method], $result);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->responses = [
            '/path-1?param_1=val1&param_2=val2&api_key=' . $this->apiKey => [
                'GET' => [
                    [
                        'getKey1' => 'getValue1',
                        'getKey2' => 'getValue2'
                    ]
                ],

                'POST' => [
                    [
                        'postKey1' => 'postValue1',
                        'postKey2' => 'postValue2'
                    ]
                ]
            ],

            '/path-2?api_key=' . $this->apiKey => [
                'GET' => [
                    [
                        'getKey' => 'getValue'
                    ]
                ]
            ],

            '/path-2?api_key=some_another_key' => [
                'GET' => [
                    [
                        'getKeyNew' => 'getValueNew'
                    ]
                ]
            ]
        ];
    }

    /**
     * @return AbstractModule
     */
    private function getModule()
    {
        $parametersValidator = new Parameters([
            'common' => 'api_key',
            '/path-1' => 'param_1 && param_2'
        ]);
        $module = $this->getMockForAbstractClass(AbstractModule::class, [$this->getHttpClient(), $parametersValidator]);

        /**
         * @var AbstractModule $module
         */
        return $module->setApiKey($this->apiKey);
    }

    /**
     * @param object $object
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    private function callNonPublicMethod($object, $name, array $arguments)
    {
        $reflectionMethod = new ReflectionMethod($object, $name);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($object, $arguments);
    }
}
