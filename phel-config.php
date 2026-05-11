<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;

return PhelConfig::forProject('cli-skeleton.main')
    ->withMainPhpPath('out/main.php')
    ->withIgnoreWhenBuilding(['local.phel'])
    ->withExportFromDirectories(['src/modules']);
