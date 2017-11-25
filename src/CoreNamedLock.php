<?php
//----------------------------------------------------------------------------------------------------------------------
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
    Abc::$DL->abcLockNamedAcquireLock(Abc::$companyResolver->getCmpId(), $id);

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

    return Abc::$DL->abcLockNamedGetName($this->lnnId);
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
