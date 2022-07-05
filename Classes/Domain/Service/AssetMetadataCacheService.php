<?php
namespace Netlogix\AssetMetadata\Domain\Service;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Fusion\Core\Cache\ContentCache;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadata;

/**
 * @Flow\Scope("singleton")
 */
class AssetMetadataCacheService
{

    /**
     * @Flow\Inject
     * @var ContentCache
     */
    protected $contentCache;

    /**
     * @var ObjectManagerInterface
     * @Flow\Inject
     */
    protected $objectManager;

    public function handleMetadataChanged(AssetMetadata $metadata): void
    {
        $cacheTag = $this->generateCacheTagForMetadataName($metadata->getMetadataName());

        $this->contentCache->flushByTag($cacheTag);

        // FIXME: This should not be part of Netlogix.AssetMetadata
        if ($varnishBanService = $this->getClassInstanceIfExists(\MOC\Varnish\Service\VarnishBanService::class)) {
            $varnishBanService->banByTags([$cacheTag]);
        }
        if ($varnishBanService = $this->getClassInstanceIfExists(\Flowpack\Varnish\Service\VarnishBanService::class)) {
            $varnishBanService->banByTags([$cacheTag]);
        }
    }

    public function generateCacheTagForMetadataName(string $metadataName): string
    {
        return 'AssetMetadata_' . ucfirst($metadataName);
    }

    private function getClassInstanceIfExists(string $className): ?object
    {
        if (!class_exists($className)) {
            return null;
        }

        return $this->objectManager->get($className);
    }

}
