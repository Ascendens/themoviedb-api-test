<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\Discover;

class DiscoverTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /**
     * @var string
     */
    private $apiKey = 'some_key';

    /**
     * @test
     */
    public function movie()
    {
        /**
         * @var Discover $module
         */
        $module = $this->getModule();
        $uri = array_keys($this->responses)[0];
        $result = $module->movie();
        $this->assertEquals($this->responses[$uri]['GET'], $result);
    }

    /**
     * @test
     */
    public function tv()
    {
        /**
         * @var Discover $module
         */
        $module = $this->getModule();
        $uri = array_keys($this->responses)[1];
        $result = $module->tv();
        $this->assertEquals($this->responses[$uri]['GET'], $result);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->moduleClassName = Discover::class;
        $this->responses = [
            '/discover/movie?api_key=' . $this->apiKey => [
                'GET' => [
                    'discoverMovieKey' => 'discoverMovieValue'
                ]
            ],
            '/discover/tv?api_key=' . $this->apiKey => [
                'GET' => [
                    'discoverTvKey' => 'discoverTvValue'
                ]
            ],
        ];
    }
}
