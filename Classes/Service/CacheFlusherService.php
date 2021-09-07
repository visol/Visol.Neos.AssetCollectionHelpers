<?php

namespace Visol\Neos\AssetCollectionHelpers\Service;

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
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Fusion\Core\Cache\ContentCache;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Scope("singleton")
 */
class CacheFlusherService
{
    /**
     * @Flow\Inject
     * @var ContentCache
     */
    protected $contentCache;

    /**
     * @var LoggerInterface
     */
    protected $systemLogger;

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function injectLogger(LoggerInterface $logger)
    {
        $this->systemLogger = $logger;
    }

    /**
     * @var PersistenceManagerInterface
     * @Flow\Inject
     */
    protected $persistenceManager;

    /**
     * Flush all content cache entries of the AssetCollections associated with the given Asset
     *
     * @param AssetInterface $asset
     */
    public function flushAssetCollectionCacheForAsset(AssetInterface $asset)
    {
        if (!$asset->getAssetCollections()) {
            return;
        }
        foreach ($asset->getAssetCollections() as $assetCollection) {
            /** @var AssetCollection $assetCollection */
            $this->flushCacheEntriesForAssetCollection($assetCollection);
        }
    }

    /**
     * Flushes all content cache entries for a given AssetCollection
     *
     * @param AssetCollection $assetCollection
     */
    public function flushCacheEntriesForAssetCollection(AssetCollection $assetCollection)
    {
        $assetCollectionIdentifier = $this->persistenceManager->getIdentifierByObject($assetCollection);
        $cacheTag = 'Neos_Media_AssetCollection_' . $assetCollectionIdentifier;
        $affectedEntries = $this->contentCache->flushByTag($cacheTag);
        if ($affectedEntries > 0) {
            $this->systemLogger->debug(sprintf('Content cache: Removed %s entries for asset collection %s',
                $affectedEntries, $assetCollectionIdentifier));
        }
    }

}
