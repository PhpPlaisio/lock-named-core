<?php
declare(strict_types=1);

namespace Plaisio\Lock\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\C;
use Plaisio\Kernel\Nub;
use Plaisio\Lock\CoreNamedLock;

/**
 * Test cases for CoreNamedLock.
 */
class CoreNamedLockTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The kernel.
   *
   * @var Nub
   */
  protected $kernel;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test locking twice (or more) the same named lock is possible.
   */
  public function testDoubleLock(): void
  {
    $lock = new CoreNamedLock();

    $lock->acquireLock(C::LNN_ID_NAMED_LOCK1);
    $lock->acquireLock(C::LNN_ID_NAMED_LOCK1);
    $lock->acquireLock(C::LNN_ID_NAMED_LOCK1);

    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test lock is exclusive and released on commit.
   */
  public function testExclusiveLock1(): void
  {
    // Start helper process
    $descriptors = [0 => ["pipe", "r"],
                    1 => ["pipe", "w"]];

    // As of PHP 7.4.0 we can pass an array of command line parameters.
    $cmd     = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(__DIR__.'/test-exclusive-lock-helper.php'));
    $process = proc_open($cmd, $descriptors, $pipes);

    // Acquire lock.
    Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);

    // Tell helper process to acquire lock too.
    fwrite($pipes[0], "\n");

    // Do something.
    sleep(4);

    // Release lock.
    Nub::$nub->DL->commit();

    // Read lock waiting time from child process.
    $time = fgets($pipes[1]);

    self::assertGreaterThanOrEqual(3, $time);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test lock is exclusive and released on rollback.
   */
  public function testExclusiveLock2(): void
  {
    // Start helper process
    $descriptors = [0 => ["pipe", "r"],
                    1 => ["pipe", "w"]];

    // As of PHP 7.4.0 we can pass an array of command line parameters.
    $cmd     = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(__DIR__.'/test-exclusive-lock-helper.php'));
    $process = proc_open($cmd, $descriptors, $pipes);

    // Acquire lock.
    Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);

    // Tell helper process to acquire lock too.
    fwrite($pipes[0], "\n");

    // Do something.
    sleep(4);

    // Release lock.
    Nub::$nub->DL->rollback();

    // Read lock waiting time from child process.
    $time = fgets($pipes[1]);

    self::assertGreaterThanOrEqual(3, $time);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test locks are company isolated.
   */
  public function testExclusiveLock3(): void
  {
    $this->kernel = new TestKernelSys();

    // Start helper process
    $descriptors = [0 => ["pipe", "r"],
                    1 => ["pipe", "w"]];

    // As of PHP 7.4.0 we can pass an array of command line parameters.
    $cmd     = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(__DIR__.'/test-exclusive-lock-helper.php'));
    $process = proc_open($cmd, $descriptors, $pipes);

    // Acquire lock.
    Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);

    // Tell helper process to acquire lock too.
    fwrite($pipes[0], "\n");

    // Do something.
    sleep(4);

    // Release lock.
    Nub::$nub->DL->commit();

    // Read lock waiting time from child process.
    $time = fgets($pipes[1]);

    self::assertEquals(0, $time);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get ID of named lock.
   */
  public function testGetId1(): void
  {
    $lock = Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);
    $id   = $lock->getId();

    self::assertSame(C::LNN_ID_NAMED_LOCK1, $id);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get ID of named lock without lock.
   */
  public function testGetId2(): void
  {
    $lock = new CoreNamedLock();

    $this->expectException(\LogicException::class);
    $lock->getId();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get name of named lock.
   */
  public function testGetName1(): void
  {
    $lock = new CoreNamedLock();
    $lock->acquireLock(C::LNN_ID_NAMED_LOCK1);
    $name = $lock->getName();

    self::assertSame('named_lock1', $name);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test get name of named lock without lock.
   */
  public function testGetName2(): void
  {
    $lock = new CoreNamedLock();

    $this->expectException(\LogicException::class);
    $lock->getName();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test multiple named locks are possible.
   */
  public function testMultipleLocks(): void
  {
    $lock1 = Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);
    $lock2 = Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK2);

    self::assertTrue(true);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Connects to the MySQL server and cleans the BLOB tables.
   */
  protected function setUp(): void
  {
    $this->kernel = new TestKernelPlaisio();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Disconnects from the MySQL server.
   */
  protected function tearDown(): void
  {
    Nub::$nub->DL->commit();
    Nub::$nub->DL->disconnect();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
