<?php
declare(strict_types=1);

use Plaisio\C;
use Plaisio\Kernel\Nub;
use Plaisio\Lock\Test\TestKernelPlaisio;

require __DIR__.'/../vendor/autoload.php';

// Setup kernel.
$kernel = new TestKernelPlaisio();

// Start time.
$time0 = time();

// Wait for parent process.
$handle = fopen('php://stdin', 'rt');
$read   = fgets($handle);

// Acquire lock.
Nub::$nub->createNamedLock(C::LNN_ID_NAMED_LOCK1);

// End time.
$time1 = time();

echo $time1 - $time0;
