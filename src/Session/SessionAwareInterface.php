<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Session;

/**
 * Interface for modules with session requiring methods
 */
interface SessionAwareInterface
{
    /**
     * @param string $guestSessionId
     * @return SessionAwareInterface
     */
    public function setGuestSessionId($guestSessionId);

    /**
     * @return string
     */
    public function getGuestSessionId();

    /**
     * @param string $sessionId
     * @return SessionAwareInterface
     */
    public function setSessionId($sessionId);

    /**
     * @return string
     */
    public function getSessionId();
}
