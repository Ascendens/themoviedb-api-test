<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module\Validator;

use Ascendens\Tmdb\Module\Validator\Parameters;

class ParametersTest extends \PHPUnit_Framework_TestCase
{
    public function testCommonParamsOnly()
    {
        $validator = new Parameters([
            'common' => 'common_key'
        ]);
        $this->assertTrue($validator->isValid('/some-uri', ['common_key' => 'value']));
        $this->assertFalse($validator->isValid('/some-uri', []));
    }

    public function testSimpleUrlParamsOnly()
    {
        $validator = new Parameters([
            '/url-1' => 'param1'
        ]);
        $this->assertTrue($validator->isValid('/url-1', ['param1' => 'value']));
    }

    public function testComplexParamsExpression()
    {
        $validator = new Parameters([
            '/url-2' => 'param1 && (param2 || param3)'
        ]);
        $this->assertTrue($validator->isValid('/url-2', ['param1' => 'value', 'param2' => 'value']));
        $this->assertTrue($validator->isValid('/url-2', ['param1' => 'value', 'param3' => 'value']));
        $this->assertFalse($validator->isValid('/url-2', ['param2' => 'value', 'param3' => 'value']));
    }

    /**
     * @return array
     */
    public function testNoRequiredParams()
    {
        $validator = new Parameters([]);
        $this->assertTrue($validator->isValid('/url-1', ['param1' => 'value']));
        $this->assertTrue($validator->isValid('/url-2', ['param1' => 'value', 'param2' => 'value']));
    }

    /**
     * @return array
     */
    public function testErrorMessage()
    {
        $validator = new Parameters([
            'common' => 'param1 && param2',
            '/url-1' => 'param3 || param4'
        ]);
        $this->assertFalse($validator->isValid('/url-1', []));
        $this->assertEquals(sprintf('Condition is not met: %s', '(param1 && param2) && (param3 || param4)'), $validator->getLastError());
    }

    public function testRegExpUri()
    {
        $validator = new Parameters([
            'common' => 'param1 && param2',
            '/url-3/\d+/action' => 'param3 || param4'
        ]);
        $this->assertTrue($validator->isValid('/url-3/155/action', ['param1'=> 1, 'param2' => 2, 'param4' => 4]));
    }
}
