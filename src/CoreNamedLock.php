<?php

namespace SetBased\Abc\Lock;

use SetBased\Abc\Abc;
use SetBased\Exception\LogicException;

/**
 * Class for named locks that are released on commit or rollback.
 *
 * The names of the locks are unique with respect to the schema of the data layer and company of ABC.
 */
class CoreNamedLock implements NamedLock
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Time in seconds for waiting for a lock before giving up.
   *
   * @var int
   */
  public static $lockWaitTimeout = 1073741824;

  /**
   * The ID of the lock.
   *
   * @var int|null
   */
  private $lnnId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function acquireLock($id)
  {
    Abc::$DL->abcLockNamedCoreAcquireLock(Abc::$companyResolver->getCmpId(), $id, static::$lockWaitTimeout);

    $this->lnnId = $id;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getId()
  {
    $this->ensureHoldLock();

    return $this->lnnId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    $this->ensureHoldLock();

    return Abc::$DL->abcLockNamedCoreGetName($this->lnnId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Throws an exception if this object was never used to hold a lock.
   */
  private function ensureHoldLock()
  {
    if ($this->lnnId===null)
    {
      throw new LogicException('No entity is locked');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
