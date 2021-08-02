<?php
namespace Netlogix\AssetMetadata\TypeConverter;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\TypedArrayConverter;

class AssetMetadataArrayConverter extends TypedArrayConverter
{

    /**
     * @Flow\InjectConfiguration(path="metadata")
     * @var array<string, array<string, string>>
     */
    protected $metadataSettings = [];

    protected $priority = 100;

    /**
     * @param mixed $source
     * @param string $targetType
     * @return bool
     */
    public function canConvertFrom($source, $targetType)
    {
        return $targetType === 'array<Netlogix\AssetMetadata\Domain\Model\AssetMetadata>';
    }

    public function getTypeOfChildProperty(
        $targetType,
        $propertyName,
        PropertyMappingConfigurationInterface $configuration
    ) {
        if (array_key_exists($propertyName, $this->metadataSettings)) {
            return $this->metadataSettings[$propertyName]['className'];
        }

        return parent::getTypeOfChildProperty($targetType, $propertyName, $configuration);
    }


}
