<?php
declare(strict_types=1);

namespace Plaisio\Lock\Test;

use Plaisio\C;
use Plaisio\CompanyResolver\CompanyResolver;
use Plaisio\CompanyResolver\UniCompanyResolver;
use Plaisio\Lock\CoreNamedLockFactory;
use Plaisio\PlaisioKernel;
use SetBased\Stratum\MySql\MySqlDefaultConnector;

/**
 * Kernel for testing purposes.
 */
class TestKernelSys extends PlaisioKernel
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the helper object for deriving the company.
   *
   * @return CompanyResolver
   */
  public function getCompany(): CompanyResolver
  {
    return new UniCompanyResolver(C::CMP_ID_SYS);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the helper object for creating  named locks.
   *
   * @return CoreNamedLockFactory
   */
  public function getNamedLock(): CoreNamedLockFactory
  {
    return new CoreNamedLockFactory($this);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the data layer generated by PhpStratum.
   *
   * @return Object
   */
  protected function getDL(): Object
  {
    $connector = new MySqlDefaultConnector('127.0.0.1', 'test', 'test', 'test');
    $dl        = new TestDataLayer($connector);
    $dl->connect();
    $dl->begin();

    return $dl;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
