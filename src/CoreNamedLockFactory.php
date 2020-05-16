<?php
declare(strict_types=1);

namespace Plaisio\Lock;

use Plaisio\PlaisioObject;

/**
 * Factory for creating named locks.
 */
class CoreNamedLockFactory extends PlaisioObject implements NamedLockFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritDoc
   */
  public function create(int $id): NamedLock
  {
    $lock = new CoreNamedLock($this);
    $lock->acquireLock($id);

    return $lock;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
