<?php
declare(strict_types=1);

namespace Netlogix\AssetMetadata\ViewHelpers;

use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Flow\Annotations as Flow;

class MetadataWithPartialViewHelper extends AbstractViewHelper
{

    /**
     * @Flow\InjectConfiguration(path="metadata")
     * @var array<string, array<string, string>>
     */
    protected $metadataSettings = [];

    /**
     * @return array<string, array<string, string>>
     */
    public function render(): array
    {
        return array_filter($this->metadataSettings, static function(array $configuration) {
            return ($configuration['editPartialName'] ?? false) !== false;
        });
    }

}
