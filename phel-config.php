<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelBuildConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setFormatDirs(['src', 'tests'])
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setKeepGeneratedTempFiles(false)
    ->setBuildConfig((new PhelBuildConfig())
        ->setMainPhelNamespace('cli-skeleton\main')
        ->setMainPhpPath('out/main.php'))
    ->setExportConfig((new PhelExportConfig())
        ->setFromDirectories(['src/modules'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'))
;
