<?php

namespace App\Engine\Traits;

/**
 * Trait Users.
 */
trait Users
{
    /**
     * @return bool
     */
    public function isUserAuth()
    {
        return true === array_key_exists('auth', $this->session->data) && true === $this->session->data['auth'];
    }

    /**
     * @return bool|int
     */
    public function isUserId()
    {
        $result = 0;

        if (true === array_key_exists('user_id', $this->session->data)) {
            $result = (int) $this->session->data['user_id'];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isUserinfo()
    {
        $result = false;

        if (true === array_key_exists('userinfo', $this->session->data) && 0 !== count($this->session->data['userinfo'])) {
            $result = $this->session->data['userinfo'];
        }

        return $result;
    }
}
