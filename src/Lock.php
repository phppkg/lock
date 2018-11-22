<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:52
 */

namespace PhpComp\Lock;

/**
 * Class Lock
 * @package PhpComp\Lock
 */
class Lock
{
    const DRIVER_FILE = 'file';
    const DRIVER_DB   = 'db';
    const DRIVER_MEM  = 'mem';
    const DRIVER_SEM  = 'sem';

    /**
     * @var array
     */
    private static $driverMap = [
        self::DRIVER_FILE => FileLock::class,
        self::DRIVER_DB => DatabaseLock::class,
        self::DRIVER_SEM => SemaphoreLock::class,
        self::DRIVER_MEM => MemcacheLock::class,
    ];

    /**
     * Lock constructor.
     * @param array $options
     * @param string $driver
     * @return LockInterface
     * @throws \RuntimeException
     */
    public static function make(array $options = [], $driver = null)
    {
        $class = null;

        if (!$driver && isset($options['driver'])) {
            $driver = $options['driver'];
            unset($options['driver']);
        }

        /** @var LockInterface $class */
        if (isset(self::$driverMap[$driver])) {
            $class = self::$driverMap[$driver];

        } else {
            foreach ([self::DRIVER_SEM, self::DRIVER_FILE] as $name) {
                $class = self::$driverMap[$name];

                if ($class::isSupported()){
                    break;
                }
            }
        }

        if (!$class) {
            throw new \RuntimeException('No available driver! MAP: ' . implode(',', self::$driverMap));
        }

        return new $class($options);
    }

    /**
     * @return array
     */
    public static function getDriverMap(): array
    {
        return self::$driverMap;
    }

    /**
     * {@inheritDoc}
     */
    public static function isSupported(): bool
    {
        /** @var LockInterface $class */
        foreach ([self::DRIVER_SEM, self::DRIVER_FILE] as $name) {
            $class = self::$driverMap[$name];

            if ($class::isSupported()){
                return true;
            }
        }

        return false;
    }
}
