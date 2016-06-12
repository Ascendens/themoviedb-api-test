<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use PHPUnit_Framework_TestCase;
use Ascendens\Tmdb\Module\Configuration;

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    use TestTrait;

    /**
     * @var string
     */
    private $apiKey = 'some_key';

    /**
     * @test
     */
    public function get()
    {
        /**
         * @var Configuration $module
         */
        $module = $this->getModule();
        $result = $module->get();
        $uri = array_keys($this->responses)[0];
        $this->assertEquals($this->responses[$uri]['GET'], $result);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->moduleClassName = Configuration::class;
        $this->responses = [
            '/configuration?api_key=' . $this->apiKey => [
                'GET' => [
                    'images' => [
                        'base_url' => 'http://some-url.com',
                        'backdrop_sizes' => ['w300', 'w780', 'w1280', 'original']
                    ]
                ]
            ]

        ];
    }
}
