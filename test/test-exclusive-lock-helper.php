<?php
declare(strict_types=1);

use Plaisio\C;
use Plaisio\CompanyResolver\UniCompanyResolver;
use Plaisio\Kernel\Nub;
use Plaisio\Lock\CoreNamedLock;
use Plaisio\Lock\Test\TestDataLayer;

require __DIR__.'/../vendor/autoload.php';

// Setup Plaisio.
Nub::$DL              = new TestDataLayer();
Nub::$companyResolver = new UniCompanyResolver(C::CMP_ID_PLAISIO);
Nub::$DL->connect('localhost', 'test', 'test', 'test');
Nub::$DL->begin();

// Start time.
$time0 = time();

// Wait for parent process.
$handle = fopen('php://stdin', 'rt');
$read = fgets($handle);

// Acquire lock.
$lock = new CoreNamedLock();
$lock->acquireLock(C::LNN_ID_NAMED_LOCK1);

// End time.
$time1 = time();

echo $time1 - $time0;
