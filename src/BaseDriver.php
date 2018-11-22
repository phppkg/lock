<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午10:07
 */

namespace PhpComp\Lock;

/**
 * Class BaseDriver
 * @package PhpComp\Lock
 */
abstract class BaseDriver implements LockInterface
{
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
     * Method to get property Options
     * @return  array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Method to set property options
     * @param  array $options
     * @param  bool $merge
     * @return static Return self to support chaining.
     */
    public function setOptions(array $options, $merge = true)
    {
        $this->options = $merge ? array_merge($this->options, $options) : $options;

        return $this;
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
    public static function isSupported(): bool
    {
        return true;
    }
}
