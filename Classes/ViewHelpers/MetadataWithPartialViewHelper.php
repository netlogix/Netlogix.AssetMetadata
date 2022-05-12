<?php
declare(strict_types=1);

namespace Netlogix\AssetMetadata\ViewHelpers;

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\AssetInterface;
use Netlogix\AssetMetadata\Domain\Service\AssetMetadataService;

class MetadataWithPartialViewHelper extends AbstractViewHelper
{

    /**
     * @var AssetMetadataService
     * @Flow\Inject
     */
    protected $assetMetadataService;

    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument(
            'asset',
            AssetInterface::class,
            'The asset for which to return the metadata configuration',
            true
        );
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function render(): array
    {
        $asset = $this->arguments['asset'];
        assert($asset instanceof AssetInterface);

        return $this->assetMetadataService->getMetadataSettingsWithPartialForAssetSourceIdentifier(
            $asset->getAssetSourceIdentifier()
        );
    }

}
