<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Test\Module;

use Ascendens\Tmdb\Module\ModuleFactory;
use Ascendens\Tmdb\Module\ModuleInterface;
use Closure;
use InvalidArgumentException;

class ModuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddOneFactory()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $module1 = $this->getModuleMock();
        $factory->addModuleFactory('module1', function () use ($module1) {
            return $module1;
        });
        $this->assertEquals($module1, $factory->make('module1'));
    }

    public function testKeyAndUriMatching()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $moduleFactory = Closure::bind(function ($baseUri, $apiKey) {
            $module = $this->getMockBuilder(ModuleInterface::class)->getMock();
            $module->method('getBaseUri')->willReturn($baseUri);
            $module->method('getApiKey')->willReturn($apiKey);

            return $module;
        }, $this);
        $factory->addModuleFactory('module1', $moduleFactory);
        $module = $factory->make('module1');
        $this->assertEquals('http://some-uri', $module->getBaseUri());
        $this->assertEquals('some_key', $module->getApiKey());
    }

    public function testAddFewFactories()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $module1 = $this->getModuleMock();
        $factory->addModuleFactory('module1', function () use ($module1) {
            return $module1;
        });
        $this->assertEquals($module1, $factory->make('module1'));
        $module2 = $this->getModuleMock();
        $factory->addModuleFactory('module2', function () use ($module2) {
            return $module2;
        });
        $this->assertEquals($module2, $factory->make('module2'));
    }

    /**
     * @expectedException \Ascendens\Tmdb\Exception\ModuleNotFoundException
     */
    public function testNonExistentModule()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $factory->make('none');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIncorrectFactoryResult()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $factory->addModuleFactory('module1', function () {
            return true;
        });
        $factory->make('module1');
    }

    public function testCreatedModulesCaching()
    {
        $factory = new ModuleFactory('http://some-uri', 'some_key');
        $module1 = $this->getModuleMock();
        $factory->addModuleFactory('module1', function () use ($module1) {
            return $module1;
        });
        $firstMake = $factory->make('module1');
        $secondMake = $factory->make('module1');
        $this->assertEquals($firstMake, $secondMake);
    }

    /**
     * @return ModuleInterface
     */
    private function getModuleMock()
    {
        $module = $this->getMockBuilder(ModuleInterface::class)->getMock();

        return $module;
    }
}
