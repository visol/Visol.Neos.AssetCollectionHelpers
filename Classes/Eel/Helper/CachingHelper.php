<?php

namespace Visol\Neos\AssetCollectionHelpers\Eel\Helper;

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
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetCollection;

class CachingHelper implements ProtectedContextAwareInterface
{

    /**
     * @var PersistenceManagerInterface
     * @Flow\Inject
     */
    protected $persistenceManager;

    /**
     * @param AssetCollection $assetCollection
     * @return string
     */
    public function assetCollectionTag(AssetCollection $assetCollection = null)
    {
        if (!$assetCollection instanceof AssetCollection) {
            return null;
        }
        $assetCollectionIdentifier = $this->persistenceManager->getIdentifierByObject($assetCollection);
        return 'Neos_Media_AssetCollection_' . $assetCollectionIdentifier;
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }

}
