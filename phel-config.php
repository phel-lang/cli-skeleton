<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelOutConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setOut(
        (new PhelOutConfig())
            ->setDestDir('out')
            ->setMainPhelNamespace('cli-skeleton\main')
            ->setMainPhpFilename('index')
    )
    ->setFormatDirs(['src', 'tests'])
    ->setExport(
        (new PhelExportConfig())
            ->setDirectories(['src/modules'])
            ->setNamespacePrefix('PhelGenerated')
            ->setTargetDirectory('src/PhelGenerated')
    )
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setKeepGeneratedTempFiles(false);
