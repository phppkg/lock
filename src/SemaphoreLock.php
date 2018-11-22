<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:53
 */

namespace PhpComp\Lock;

/**
 * Class SemaphoreLock - Semaphore
 * @package PhpComp\Lock
 */
class SemaphoreLock extends BaseDriver
{
    /**
     * A numeric shared memory segment ID
     * @var int
     */
    private $key;

    /**
     * The semaphore id
     * @var resource
     */
    private $semId;

    /**
     * @var array
     */
    protected $options = [
        'key' => null,
        'project' => 0,
        'permission' => 0664,
    ];

    protected function init()
    {
        parent::init();

        $this->driver = Lock::DRIVER_SEM;

        if ($this->options['key'] > 0) {
            $this->key = (int)$this->options['key'];
        } else {
            // 定义信号量key
            $this->key = $this->options['key'] = self::ftok(__FILE__, $this->options['project']);
        }

        $this->semId = sem_get($this->key, 1, $this->options['permission']);
    }

    /**
     * {@inheritdoc}
     */
    public function lock($key, $timeout = self::EXPIRE): bool
    {
        return sem_acquire($this->semId); // , $noWait = false
    }

    /**
     * {@inheritdoc}
     */
    public function unlock($key): bool
    {
        return sem_release($this->semId);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        parent::close();

        sem_remove($this->semId);

        $this->semId = null;
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return \function_exists('sem_get');
    }

    /**
     * @param string $pathname
     * @param int|string $projectId This must be a one character
     * @return int|string
     * @throws \LogicException
     */
    public static function ftok(string $pathname, $projectId)
    {
        if (\strlen($projectId) > 1) {
            throw new \LogicException("the project id must be a one character(int/str). Input: $projectId");
        }

        if (\function_exists('ftok')) {
            return ftok($pathname, $projectId);
        }

        if (!$st = @stat($pathname)) {
            return -1;
        }

        $key = sprintf('%u', ($st['ino'] & 0xffff) | (($st['dev'] & 0xff) << 16) | (($projectId & 0xff) << 24));

        return $key;
    }
}
