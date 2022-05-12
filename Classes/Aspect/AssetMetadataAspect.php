<?php
namespace Netlogix\AssetMetadata\Aspect;

use Exception;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\Asset;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadata;
use Netlogix\AssetMetadata\Domain\Repository\AssetMetadataRepository;
use Netlogix\AssetMetadata\Domain\Service\AssetMetadataService;
use Netlogix\AssetMetadata\Exception\MetadataNotAllowedForAssetSource;

/**
 * @Flow\Aspect
 * @Flow\Introduce(pointcutExpression="class(Neos\Media\Domain\Model\Asset)", interfaceName="Netlogix\AssetMetadata\Domain\Model\AssetMetadataAwareInterface")
 */
class AssetMetadataAspect
{

    /**
     * @Flow\Inject
     * @var AssetMetadataRepository
     */
    protected $assetMetadataRepository;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var AssetMetadataService
     */
    protected $assetMetadataService;

    /**
     * @Flow\Around("method(Neos\Media\Domain\Model\Asset->getMetadata())")
     *
     * @param JoinPointInterface $joinPoint
     *
     * @return AssetMetadata|array<string, AssetMetadata>
     */
    public function getMetadata(JoinPointInterface $joinPoint)
    {
        /** @var Asset $asset */
        $asset = $joinPoint->getProxy();
        $metadataName = $joinPoint->getMethodArgument('metadataName');

        if ($metadataName !== null) {
            $this->validateAssetMetadata($asset, $metadataName);

            $metadata = $this->assetMetadataRepository->findOneByAssetAndName($asset, $metadataName);
        } else {
            /** @var AssetMetadata[] $metadataList */
            $metadataList = $this->assetMetadataRepository->findByAsset($asset)->toArray();
            $metadata = [];
            foreach ($metadataList as $metadataItem) {
                $this->validateAssetMetadata($asset, $metadataItem->getMetadataName());

                $metadata[$metadataItem->getMetadataName()] = $metadataItem;
            }
        }

        return $metadata;
    }

    /**
     * @Flow\Around("method(Neos\Media\Domain\Model\Asset->setMetadata())")
     *
     * @param JoinPointInterface $joinPoint
     * @return void
     * @throws Exception
     */
    public function setMetadata(JoinPointInterface $joinPoint): void
    {
        /** @var Asset $asset */
        $asset = $joinPoint->getProxy();
        /** @var AssetMetadata[] $metadata */
        $metadata = $joinPoint->getMethodArgument('metadata');

        foreach ($metadata as $metadataItem) {
            $this->validateAssetMetadata($asset, $metadataItem->getMetadataName());

            if ($this->persistenceManager->isNewObject($metadataItem)) {
                $this->assetMetadataRepository->add($metadataItem);
            } else {
                $this->assetMetadataRepository->update($metadataItem);
            }
            $this->persistenceManager->allowObject($metadataItem);
        }

        $this->persistenceManager->persistAll(true);

        array_walk($metadata, function (AssetMetadata $metadata) {
            $this->assetMetadataService->emitAssetMetadataChanged($metadata);
        });
    }

    private function validateAssetMetadata(Asset $asset, string $metadataName): void
    {
        $assetSourceIdentifier = $asset->getAssetSourceIdentifier();
        $allowedMetadata = $this->assetMetadataService->getMetadataSettingsForAssetSourceIdentifier(
            $assetSourceIdentifier
        );

        $isAllowed = in_array($metadataName, array_keys($allowedMetadata), true);
        if (!$isAllowed) {
            throw new MetadataNotAllowedForAssetSource(
                sprintf('Metadata "%s" is not allowed for asset source "%s"', $metadataName, $assetSourceIdentifier),
                1652362247
            );
        }
    }

}
