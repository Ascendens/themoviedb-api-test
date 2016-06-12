<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module;

interface ModuleFactoryInterface
{
    /**
     * Finds appropriate module
     *
     * @param string $codename
     * @return ModuleInterface
     */
    public function make($codename);
}
