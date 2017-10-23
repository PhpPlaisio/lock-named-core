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
   * @param int $pLnmId The ID of the named lock.
   *                    smallint(5) unsigned
   *
   * @return int
   */
  public static function abcLockNamedGetLock($pCmpId, $pLnmId)
  {
    return self::executeNone('CALL abc_lock_named_get_lock('.self::quoteNum($pCmpId).','.self::quoteNum($pLnmId).')');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Selects the name of a named lock.
   *
   * @param int $pCmpId The ID of the company (safeguard).
   *                    smallint(5) unsigned
   * @param int $pLnmId The ID of the named lock.
   *                    smallint(5) unsigned
   *
   * @return string|null
   */
  public static function abcLockNamedGetName($pCmpId, $pLnmId)
  {
    return self::executeSingleton1('CALL abc_lock_named_get_name('.self::quoteNum($pCmpId).','.self::quoteNum($pLnmId).')');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
