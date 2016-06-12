<?php
/**
 * @copyright Copyright (c) 2016 Andrey Kotelnik <uarockfan@gmail.com>
 * @license   https://github.com/Ascendens/themoviedb-api-test/blob/master/LICENSE
 */

namespace Ascendens\Tmdb\Session;

trait SessionAwareTrait
{
    /**
     * @var string
     */
    private $guestSessionId;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @inheritdoc
     */
    public function setGuestSessionId($guestSessionId)
    {
        $this->guestSessionId = (string) $guestSessionId;
    }

    /**
     * @inheritdoc
     */
    public function getGuestSessionId()
    {
        return $this->guestSessionId;
    }

    /**
     * @inheritdoc
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = (string) $sessionId;
    }

    /**
     * @inheritdoc
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}
