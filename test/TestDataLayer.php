<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Test;

use SetBased\Stratum\MySql\StaticDataLayer;

//----------------------------------------------------------------------------------------------------------------------
/**
 * The data layer.
 */
class TestDataLayer extends StaticDataLayer
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Acquires a named lock.
   *
   * @param int $pCmpId The ID of the company (safeguard).
   *                    smallint(5) unsigned
   * @param int $pLnnId The ID of the named lock.
   *                    smallint(6)
   *
   * @return int
   */
  public static function abcLockNamedAcquireLock($pCmpId, $pLnnId)
  {
    return self::executeNone('CALL abc_lock_named_acquire_lock('.self::quoteNum($pCmpId).','.self::quoteNum($pLnnId).')');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Selects the name of a named lock.
   *
   * @param int $pLnnId The ID of the named lock.
   *                    smallint(6)
   *
   * @return string|null
   */
  public static function abcLockNamedGetName($pLnnId)
  {
    return self::executeSingleton1('CALL abc_lock_named_get_name('.self::quoteNum($pLnnId).')');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
