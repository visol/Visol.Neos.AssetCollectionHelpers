<?php

namespace Visol\Neos\AssetCollectionHelpers\DataSources;

/*
 * This file is part of the Visol.Neos.AssetCollectionHelpers package.
 *
 * Credits: https://discuss.neos.io/t/guide-how-to-select-entities-in-the-inspector-neos-2-3-lts/1664
 *
 * (c) visol digitale Dienstleistungen GmbH, www.visol.ch
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Utility\TypeHandling;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\ContentRepository\Domain\Model\NodeInterface;

class AssetCollectionDataSource extends AbstractDataSource
{

    /**
     * @var string
     */
    static protected $identifier = 'visol-neos-assetcollections';

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var \Neos\Media\Domain\Repository\AssetCollectionRepository
     */
    protected $assetCollectionRepository;

    /**
     * @param NodeInterface|null $node
     * @param array $arguments
     * @return array
     */
    public function getData(NodeInterface $node = null, array $arguments)
    {
        // Empty value
        $options = [['label' => '-', 'value' => '']];
        $assetCollections = $this->assetCollectionRepository->findAll();
        foreach ($assetCollections as $assetCollection) {
            /** @var \Neos\Media\Domain\Model\AssetCollection $assetCollection */
            $options[] = [
                'label' => $assetCollection->getTitle(),
                'value' => [
                    '__identity' => $this->persistenceManager->getIdentifierByObject($assetCollection),
                    '__type' => TypeHandling::getTypeForValue($assetCollection)
                ]
            ];
        }

        return $options;
    }

}
