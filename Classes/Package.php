<?php
namespace Netlogix\AssetMetadata;

use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;
use Neos\Media\Domain\Service\AssetService;
use Netlogix\AssetMetadata\Domain\Service\AssetMetadataCacheService;
use Netlogix\AssetMetadata\Domain\Service\AssetMetadataService;

class Package extends BasePackage
{

    /**
     * @param Bootstrap $bootstrap
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect(
            AssetService::class,
            'assetRemoved',
            AssetMetadataService::class,
            'handleAssetRemoved'
        );
        $dispatcher->connect(
            AssetMetadataService::class,
            'assetMetadataChanged',
            AssetMetadataCacheService::class,
            'handleMetadataChanged'
        );
    }

}
