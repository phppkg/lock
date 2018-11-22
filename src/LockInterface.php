<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:53
 */

namespace PhpComp\Lock;

/**
 * Interface LockInterface
 * @package PhpComp\Lock
 */
interface LockInterface
{
    /**
     * seconds
     */
    const EXPIRE = 5;

    /**
     * @param string $key
     * @param int $timeout
     * @return bool
     */
    public function lock($key, $timeout = self::EXPIRE): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function unlock($key): bool;

    /**
     * close
     */
    public function close();

    /**
     * @return string
     */
    public function getDriver(): string;

    /**
     * @return bool
     */
    public static function isSupported(): bool;
}
