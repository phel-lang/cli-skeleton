<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelBuildConfig;
use Phel\Config\PhelExportConfig;

return PhelConfig::forProject('cli-skeleton.main')
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setBuildConfig((new PhelBuildConfig())
        ->setMainPhpPath('out/main.php'))
    ->setExportConfig((new PhelExportConfig())
        ->setFromDirectories(['src/modules'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'));
