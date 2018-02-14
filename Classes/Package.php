<?php

namespace Visol\Neos\AssetCollectionHelpers;

/*
 * This file is part of the Visol.Neos.AssetCollectionHelpers package.
 *
 * (c) visol digitale Dienstleistungen GmbH, www.visol.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;
use Visol\Neos\AssetCollectionHelpers\Service\CacheFlusherService;

class Package extends BasePackage
{

    /**
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(AssetService::class, 'assetCreated', CacheFlusherService::class,
            'flushAssetCollectionCacheForAsset');
        $dispatcher->connect(AssetService::class, 'assetRemoved', CacheFlusherService::class,
            'flushAssetCollectionCacheForAsset');
        $dispatcher->connect(AssetService::class, 'assetUpdated', CacheFlusherService::class,
            'flushAssetCollectionCacheForAsset');
        $dispatcher->connect(AssetService::class, 'assetResourceReplaced', CacheFlusherService::class,
            'flushAssetCollectionCacheForAsset');
    }
}
