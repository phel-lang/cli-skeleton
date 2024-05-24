<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelBuildConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setBuildConfig((new PhelBuildConfig())
        ->setMainPhelNamespace('cli-skeleton\main')
        ->setMainPhpPath('out/main.php'))
    ->setFormatDirs(['src', 'tests'])
    ->setExportConfig((new PhelExportConfig())
        ->setFromDirectories(['src/modules'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'))
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setKeepGeneratedTempFiles(false)
;
