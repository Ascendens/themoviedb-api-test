<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module\Validator;

interface ParametersValidatorInterface
{
    /**
     * Validates given set of parameters
     *
     * @param string $uri
     * @param array $parameters
     * @return bool
     */
    public function isValid($uri, array $parameters);

    /**
     * Returns last error information
     *
     * @return string
     */
    public function getLastError();
}
