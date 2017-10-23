<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Test\CoreNamedLock;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\CompanyResolver\UniCompanyResolver;
use SetBased\Abc\Lock\CoreNamedLock;
use SetBased\Abc\Test\TestDataLayer;

/**
 * Test cases for CoreNamedLock.
 */
class CoreNamedLockTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test locking twice (or more) the same named lock is possible.
   */
  public function testDoubleLock()
  {
    $lock = new CoreNamedLock();

    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);
    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);
    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);

    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get name of named lock.
   */
  public function testGetName1()
  {
    $lock = new CoreNamedLock();

    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);
    $name = $lock->getName();

    self::assertSame('named_lock1', $name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get name of named lock without lock.
   */
  public function testGetName2()
  {
    $lock = new CoreNamedLock();

    $name = $lock->getName();

    self::assertNull($name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test multiple named locks are possible.
   */
  public function testMultipleLocks()
  {
    $lock = new CoreNamedLock();

    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);
    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK2);

    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Connects to the MySQL server and cleans the BLOB tables.
   */
  protected function setUp()
  {
    Abc::$DL              = new TestDataLayer();
    Abc::$companyResolver = new UniCompanyResolver(C::CMP_ID_ABC);

    Abc::$DL->connect('localhost', 'test', 'test', 'test');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disconnects from the MySQL server.
   */
  protected function tearDown()
  {
    Abc::$DL->disconnect();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------