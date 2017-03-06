<?php

namespace App\Engine;

/**
 * Class Config.
 */
class Config
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}
