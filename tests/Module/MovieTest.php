<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\Movie;

class MovieTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /**
     * @var string
     */
    protected $apiKey = 'some_key';

    /**
     * @test
     */
    public function get()
    {
        /**
         * @var Movie $module
         */
        $module = $this->getModule();
        $result = $module->get(1);
        $uri = array_keys($this->responses)[0];
        $this->assertEquals($this->responses[$uri]['GET'], $result);
    }

    /**
     * @test
     */
    public function setRatingWithSessionId()
    {
        /**
         * @var Movie $module
         */
        $module = $this->getModule();
        $module->setSessionId('some_sid');
        $result = $module->setRating(1, 7.5);
        $uri = array_keys($this->responses)[1];
        $this->assertEquals($this->responses[$uri]['POST'], $result);
    }

    /**
     * @test
     */
    public function deleteRatingWithGuestSessionId()
    {
        /**
         * @var Movie $module
         */
        $module = $this->getModule();
        $module->setGuestSessionId('some_gsid');
        $result = $module->deleteRating(1);
        $uri = array_keys($this->responses)[2];
        $this->assertEquals($this->responses[$uri]['DELETE'], $result);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->moduleClassName = Movie::class;
        $this->responses = [
            '/movie/1?api_key=' . $this->apiKey => [
                'GET' => [
                    'name' => 'Title',
                    'genre' => 'Action, Comedy'
                ]
            ],

            '/movie/1/rating?session_id=some_sid&api_key=' . $this->apiKey => [
                'POST' => [
                    'status_code' => 1
                ]
            ],

            '/movie/1/rating?guest_session_id=some_gsid&api_key=' . $this->apiKey => [
                'DELETE' => [
                    'status_code' => 13
                ]
            ]
        ];
    }
}
