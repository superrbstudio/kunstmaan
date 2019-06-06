<?php

namespace Superrb\KunstmaanAddonsBundle\VersionCheck;

use Kunstmaan\AdminBundle\Helper\VersionCheck\VersionChecker as BaseVersionChecker;
use Kunstmaan\AdminBundle\Helper\VersionCheck\Exception;

class VersionChecker extends BaseVersionChecker
{
    /**
     * Parse the composer.lock file to get the currently used versions of the kunstmaan bundles.
     *
     * @return array
     * @throws Exception\ParseException
     */
    protected function parseComposer()
    {
        $bundles = parent::parseComposer();
        foreach ($this->getPackages() as $package) {
            if (!strncmp($package['name'], 'superrb/', strlen('superrb/'))) {
                $bundles[] = array(
                    'name' => $package['name'],
                    'version' => $package['version'],
                    'reference' => $package['source']['reference'],
                );
            }
        }

        return $bundles;
    }
}
