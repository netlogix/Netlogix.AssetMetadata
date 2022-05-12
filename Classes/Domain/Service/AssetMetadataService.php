<?php
namespace Netlogix\AssetMetadata\Domain\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\VariantSupportInterface;
use Neos\Utility\PositionalArraySorter;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadata;
use Netlogix\AssetMetadata\Domain\Repository\AssetMetadataRepository;

/**
 * @Flow\Scope("singleton")
 */
class AssetMetadataService
{

    /**
     * @var array<string, array<string, string>>
     */
    protected $metadataSettings = [];

    /**
     * @Flow\Inject
     * @var AssetMetadataRepository
     */
    protected $assetMetadataRepository;

    public function injectSettings(array $settings): void
    {
        $metadata = $settings['metadata'];
        $sorter = new PositionalArraySorter($metadata, 'position');
        $this->metadataSettings = $sorter->toArray();
    }

    /**
     * @param AssetInterface $asset
     */
    public function handleAssetRemoved(AssetInterface $asset): void
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

    public function getMetadataSettingsForAssetSourceIdentifier(string $assetSourceIdentifier): array
    {
        $forAssetSource = array_filter($this->metadataSettings, static function(array $configuration) use ($assetSourceIdentifier) {
            $assetSources = $configuration['assetSources'] ?? null;
            if ($assetSources === null) {
                return true;
            }

            if (is_array($assetSources) && in_array($assetSourceIdentifier, $assetSources, true)) {
                return true;
            }

            return false;
        });

        return $forAssetSource;
    }

    public function getMetadataSettingsWithPartialForAssetSourceIdentifier(string $assetSourceIdentifier): array
    {
        $forAssetSource = $this->getMetadataSettingsForAssetSourceIdentifier($assetSourceIdentifier);
        $withPartial = array_filter($forAssetSource, static function(array $configuration) {
            return ($configuration['editPartialName'] ?? false) !== false;
        });

        return $withPartial;
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
    public function emitAssetMetadataChanged(AssetMetadata $metadata): void
    {
    }

}
