<?php

namespace Visol\Neos\AssetCollectionHelpers\Aspect;

/*
 * This file is part of the Visol.Neos.AssetCollectionHelpers package.
 *
 * (c) visol digitale Dienstleistungen GmbH, www.visol.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\AssetCollection;
use Visol\Neos\AssetCollectionHelpers\Service\CacheFlusherService;

/**
 * @Flow\Aspect
 */
class CacheFlushingAspect
{

    /**
     * @var CacheFlusherService
     * @Flow\Inject
     */
    protected $cacheFlusherService;

    /**
     * Triggers flushing the content cache when assets are set, added or removed to an AssetCollection
     *
     * @param  \Neos\Flow\Aop\JoinPointInterface $joinPoint
     * @return void
     * @Flow\Before("method(public Neos\Media\Domain\Model\AssetCollection->(addAsset|removeAsset|setAssets)())")
     */
    public function flushAffectedContentCacheOnAssetCollectionManipulation(
        \Neos\Flow\Aop\JoinPointInterface $joinPoint
    ) {
        /** @var AssetCollection $assetCollection */
        $assetCollection = $joinPoint->getProxy();
        $this->cacheFlusherService->flushCacheEntriesForAssetCollection($assetCollection);
    }

}
