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
   * Test lock is exclusive.
   */
  public function testExclusiveLock()
  {
    // Start helper process
    $descriptors = [0 => ["pipe", "r"],
                    1 => ["pipe", "w"]];

    $process = proc_open(__DIR__.'/../test-exclusive-lock-helper.php', $descriptors, $pipes);

    // Acquire lock.
    $lock = new CoreNamedLock();
    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);

    // Tell helper process to acquire lock too.
    fwrite($pipes[0], "\n");

    // Do something.
    sleep(4);

    // Release lock.
    Abc::$DL->commit();

    // Read lock waiting time from child process.
    $time = fgets($pipes[1]);

    self::assertGreaterThan(3, $time);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get ID of named lock.
   */
  public function testGetId1()
  {
    $lock = new CoreNamedLock();

    $lock->getLock(C::LNM_ID_ABC_NAMED_LOCK1);
    $id = $lock->getId();

    self::assertSame(C::LNM_ID_ABC_NAMED_LOCK1, $id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get ID of named lock without lock.
   */
  public function testGetId2()
  {
    $lock = new CoreNamedLock();

    $id = $lock->getId();

    self::assertNull($id);
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
    Abc::$DL->begin();
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
