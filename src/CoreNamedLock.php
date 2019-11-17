<?php
declare(strict_types=1);

namespace Plaisio\Lock;

use Plaisio\Kernel\Nub;
use SetBased\Exception\LogicException;

/**
 * Class for named locks that are released on commit or rollback.
 *
 * The names of the locks are unique with respect to the schema of the data layer and company of Plaisio.
 */
class CoreNamedLock implements NamedLock
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The value for
   * [innodb_lock_wait_timeout](https://mariadb.com/kb/en/library/xtradbinnodb-server-system-variables/#innodb_lock_wait_timeout).
   * Set to -1 for using the current value of innodb_lock_wait_timeout.
   *
   * @var int
   */
  public static $lockWaitTimeout = -1;

  /**
   * The ID of the lock.
   *
   * @var int|null
   */
  private $lnnId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function acquireLock(int $id): void
  {
    Nub::$DL->abcLockNamedCoreAcquireLock(Nub::$companyResolver->getCmpId(), $id, static::$lockWaitTimeout);

    $this->lnnId = $id;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getId(): int
  {
    $this->ensureHoldLock();

    return $this->lnnId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getName(): string
  {
    $this->ensureHoldLock();

    return Nub::$DL->abcLockNamedCoreGetName($this->lnnId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Throws an exception if this object was never used to hold a lock.
   */
  private function ensureHoldLock(): void
  {
    if ($this->lnnId===null)
    {
      throw new LogicException('No entity is locked');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
