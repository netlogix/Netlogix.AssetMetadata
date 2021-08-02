<?php
namespace Netlogix\AssetMetadata\TypeConverter;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Media\TypeConverter\AssetInterfaceConverter;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadataAwareInterface;

/**
 * @Flow\Scope("singleton")
 */
class AssetMetadataAwareConverter extends AssetInterfaceConverter
{

    protected $targetType = AssetMetadataAwareInterface::class;

    protected $priority = 100;

    public function getTypeOfChildProperty(
        $targetType,
        $propertyName,
        PropertyMappingConfigurationInterface $configuration
    ) {
        if ($propertyName === 'metadata') {
            return 'array<Netlogix\AssetMetadata\Domain\Model\AssetMetadata>';
        }

        return parent::getTypeOfChildProperty($targetType, $propertyName, $configuration);
    }

}
