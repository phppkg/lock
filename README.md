# php 共享锁的实现

[![License](https://img.shields.io/packagist/l/php-comp/lock.svg?style=flat-square)](LICENSE)
[![Php Version](https://img.shields.io/badge/php-%3E=7.0-brightgreen.svg?maxAge=2592000)](https://packagist.org/packages/php-comp/lock)
[![Latest Stable Version](http://img.shields.io/packagist/v/php-comp/lock.svg)](https://packagist.org/packages/php-comp/lock)

- `DatabaseLock` 数据库方式的共享锁
- `FileLock` 文件加锁的方式实现
- `SemaphoreLock` 基于信号量（是系统提供的一种原子操作）的方式实现。需php编译时 `--enable-sysvsem`
- `MemcacheLock` 基于memcache实现

> 参考： http://www.jb51.net/article/94878.htm

## 安装

- composer

```json
{
    "require": {
        "php-comp/lock": "dev-master"
    }
}
```

- 直接拉取

```bash
git clone https://git.oschina.net/inhere/php-lock.git // git@osc
git clone https://github.com/inhere/php-lock.git // github
```

## 使用

```php
use PhpComp\Lock\Lock;

$locker = new Lock([
    'driver' => '', // allow: File Database Memcache Semaphore
    'tmpDir' => '/tmp', // tmp path, if use FileLock
]);

$key = 'op1';

if ($locker->lock($key)) {
    // do something ...
    
    $locker->unlock($key);
}

```

## License

MIT
