<?php

declare(strict_types=1);

namespace Smile\GdprDump\Dumper;

use Smile\GdprDump\Config\ConfigInterface;

interface DumperInterface
{
    /**
     * Create a dump according to the configuration.
     *
     * @param ConfigInterface $config
     */
    public function dump(ConfigInterface $config): void;
}
