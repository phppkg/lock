<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午10:04
 */

namespace PhpComp\Lock;

/**
 * Class MemcacheLock
 * @package PhpComp\Lock
 */
class MemcacheLock extends BaseDriver
{
    const PREFIX = 'lock_';

    /**
     * @var \Memcached|\Memcache
     */
    private $mem;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $options = [])
    {
        if (isset($options['mem'])) {
            $this->setMem($options['mem']);
            unset($options['mem']);
        }

        $this->driver = Lock::DRIVER_MEM;

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    public function lock($key, $timeout = self::EXPIRE): bool
    {
        $wait = 20000;
        $totalWait = 0;
        $time = $timeout * 1000000;
        $key = self::PREFIX . $key;

        while ($totalWait < $time && false === $this->mem->add($key, 1, $timeout)) {
            usleep($wait);
            $totalWait += $wait;
        }

        if ($totalWait >= $time) {
            throw new \RuntimeException('cannot get lock for waiting ' . $timeout . 's.', __LINE__);
        }

        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function unlock($key): bool
    {
        return $this->mem->delete(self::PREFIX . $key);
    }

    /**
     * @return \Memcache|\Memcached
     */
    public function getMem()
    {
        return $this->mem;
    }

    /**
     * @param \Memcache|\Memcached $mem
     */
    public function setMem($mem)
    {
        $this->mem = $mem;
    }

}
