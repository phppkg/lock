<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午10:07
 */

namespace inhere\lock;

use inhere\library\traits\LiteOptionsTrait;

/**
 * Class BaseDriver
 * @package inhere\lock
 */
abstract class BaseDriver implements LockInterface
{
    use LiteOptionsTrait;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * BaseDriver constructor.
     * @param array $options
     * @throws \RuntimeException
     */
    public function __construct(array $options = [])
    {
        if (!static::isSupported()) {
            throw new \RuntimeException("Current environment is not support the lock driver: {$this->driver}");
        }

        $this->setOptions($options);

        $this->init();
    }

    protected function init()
    {
        // ... ...
    }

    /**
     * @param $key
     * @param \Closure $closure
     * @param int $timeout
     * @return bool|mixed
     */
    public function lockDo($key, \Closure $closure, $timeout = 3)
    {
        $ret = false;

        // lock
        if ($this->lock($key, $timeout)) {
            // operate data
            $ret = $closure();

            // unlock
            $this->unlock($key);
        }

        return $ret;
    }

    /**
     * close
     */
    public function close()
    {
        $this->options = [];
    }


    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @return bool
     */
    public static function isSupported()
    {
        return true;
    }
}
