<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Module\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Module parameters validator. Used Symfony expression language for parameters validation.
 * <code>
 * use Ascendens\Tmdb\Module\Validator\Parameters;
 *
 * $requiredMap = [
 *  'common' => 'api_key' // Parameters required for all URIs
 *  // Regexp for URI and Symfony expression for parameters
 *  '/some-uri/\d+/some-action' => '(param1 || param 2) && (param3 || param 4)' // URI specific parameters
 * ];
 * $validator = new Parameters($requiredMap);
 * $validator->isValid('/movie/5/rating', ['param2' => 'two', 'param4' => 'four']); // TRUE
 * </code>
 */
class Parameters implements ParametersValidatorInterface
{
    /**
     * @var array
     */
    protected $expected;
    
    /**
     * @var ExpressionLanguage
     */
    protected $expressionLanguage;

    /**
     * @var array
     */
    protected $lastError;

    /**
     * @param array $expected
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct(array $expected, ExpressionLanguage $expressionLanguage = null)
    {
        $this->expected = $expected;
        $this->expressionLanguage = $expressionLanguage ?: new ExpressionLanguage();
    }

    /**
     * @inheritdoc
     * @see Parameters
     */
    public function isValid($uri, array $parameters)
    {
        $this->lastError = [];
        $expression = $this->getExpression($uri);
        if (empty($expression)) {
            return true;
        }
        preg_match_all('#\w+#', $expression, $variables);
        $variables = array_merge(array_fill_keys($variables[0], null), $parameters);
        if (!$this->expressionLanguage->evaluate($expression, $variables)) {
            $this->lastError = sprintf('Condition is not met: %s', $expression);
            
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function getExpression($uri)
    {
        $conditions = [];
        foreach ($this->expected as $pattern => $parameters) {
            if ($pattern === 'common' || preg_match(sprintf('#%s#', $pattern), $uri)) {
                $conditions[] = sprintf('(%s)', $parameters);
            }
        }

        return implode(' && ', $conditions);
    }
}
