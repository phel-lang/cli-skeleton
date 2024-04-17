<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelOutConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setOut((new PhelOutConfig())
        ->setMainPhelNamespace('cli-skeleton\main')
        ->setMainPhpPath('out/main.php'))
    ->setFormatDirs(['src', 'tests'])
    ->setExport((new PhelExportConfig())
        ->setDirectories(['src/modules'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'))
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setKeepGeneratedTempFiles(false)
;
