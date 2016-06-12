<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\Authentication;

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    use TestTrait;

    /**
     * @var string
     */
    private $apiKey = 'some_key';

    public function testGetGuestSession()
    {
        /**
         * @var Authentication $module
         */
        $module = $this->getModule();
        $uri = array_keys($this->responses)[0];
        $response = $module->newGuestSession();
        $this->assertEquals($this->responses[$uri]['GET'], $response);
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->moduleClassName = Authentication::class;
        $this->responses = [
            '/authentication/guest_session/new?api_key=' . $this->apiKey => [
                'GET' => [
                    'var' => 'value'
                ]
            ]
        ];
    }
}
