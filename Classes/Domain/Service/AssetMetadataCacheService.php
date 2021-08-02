<?php
namespace Netlogix\AssetMetadata\Domain\Service;

use MOC\Varnish\Service\VarnishBanService;
use Neos\Flow\Annotations as Flow;
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
     * @Flow\Inject
     * @var VarnishBanService
     */
    protected $varnishBanService;

    public function handleMetadataChanged(AssetMetadata $metadata): void
    {
        $cacheTag = $this->generateCacheTagForMetadataName($metadata->getMetadataName());

        $this->contentCache->flushByTag($cacheTag);
        $this->varnishBanService->banByTags([$cacheTag]);
    }

    public function generateCacheTagForMetadataName(string $metadataName): string
    {
        return 'AssetMetadata_' . ucfirst($metadataName);
    }

}
