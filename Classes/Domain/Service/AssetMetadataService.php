<?php
namespace Netlogix\AssetMetadata\Domain\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\VariantSupportInterface;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadata;
use Netlogix\AssetMetadata\Domain\Repository\AssetMetadataRepository;

/**
 * @Flow\Scope("singleton")
 */
class AssetMetadataService
{

    /**
     * @Flow\Inject
     * @var AssetMetadataRepository
     */
    protected $assetMetadataRepository;

    /**
     * @param AssetInterface $asset
     */
    public function handleAssetRemoved(AssetInterface $asset)
    {
        if (!$asset instanceof Asset) {
            return;
        }

        if ($asset instanceof VariantSupportInterface) {
            foreach ($asset->getVariants() as $variant) {
                $this->removeAssetMetadata($variant);
            }
        }

        $this->removeAssetMetadata($asset);
    }

    private function removeAssetMetadata(Asset $asset): void
    {
        $metadataList = $this->assetMetadataRepository->findByAsset($asset);
        foreach ($metadataList as $metadata) {
            $this->assetMetadataRepository->remove($metadata);
        }
    }

    /**
     * @Flow\Signal
     *
     * @param AssetMetadata $metadata
     */
    public function emitAssetMetadataChanged(AssetMetadata $metadata)
    {
    }

}
