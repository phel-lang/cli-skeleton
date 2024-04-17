<?php
declare(strict_types=1);

use Phel\Phel;
use PhelGenerated\CliSkeleton\Modules\AdderModule;

$projectRootDir = dirname(__DIR__);

require $projectRootDir . '/vendor/autoload.php';

Phel::run($projectRootDir, 'cli-skeleton\modules\adder-module');

###################################################
# Remember to run phel export command to generate
# the PHP classes from the exported phel functions:
#
# $ vendor/bin/phel export
# $ php example/using-exported-phel-function.php
###################################################
$adder = new AdderModule();
$result = $adder->adder(1, 2, 3);

echo 'Result = ' . $result . PHP_EOL;
