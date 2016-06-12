<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test;

use Ascendens\Tmdb\Client;
use Ascendens\Tmdb\Test\Module\TestTrait;
use Ascendens\Tmdb\Module\Authentication;
use Ascendens\Tmdb\Module\Configuration;
use Ascendens\Tmdb\Module\Discover;
use Ascendens\Tmdb\Module\Movie;
use InvalidArgumentException;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    public function testDynamicAuthenticationCreation()
    {
        $client = new Client('some_key', $this->getHttpClient());
        $authentication = $client->authentication;
        $this->assertInstanceOf(Authentication::class, $authentication);
    }

    public function testDynamicConfigurationCreation()
    {
        $client = new Client('some_key', $this->getHttpClient());
        $configuration = $client->configuration;
        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testDynamicDiscoverCreation()
    {
        $client = new Client('some_key', $this->getHttpClient());
        $discover = $client->discover;
        $this->assertInstanceOf(Discover::class, $discover);
    }

    public function testDynamicMovieCreation()
    {
        $client = new Client('some_key', $this->getHttpClient());
        $client->setSessionId('some_sid');
        $client->setGuestSessionId('some_gsid');
        $movie = $client->movie;
        $this->assertInstanceOf(Movie::class, $movie);
        /**
         * @var Movie $movie
         */
        $this->assertEquals('some_sid', $movie->getSessionId());
        $this->assertEquals('some_gsid', $movie->getGuestSessionId());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonExistentModule()
    {
        $client = new Client('some_key', $this->getHttpClient());
        $client->none;
    }
}
