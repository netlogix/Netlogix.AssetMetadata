<?php
namespace Netlogix\AssetMetadata\TypeConverter;

use Neos\Flow\Annotations\InjectConfiguration;
use Neos\Flow\Property\PropertyMappingConfigurationInterface;
use Neos\Flow\Property\TypeConverter\TypedArrayConverter;

class AssetMetadataArrayConverter extends TypedArrayConverter
{

    /**
     * @InjectConfiguration(path="nameToClassNameMap")
     * @var array
     */
    protected $nameToClassNameMapping = [];

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
        if (array_key_exists($propertyName, $this->nameToClassNameMapping)) {
            return $this->nameToClassNameMapping[$propertyName];
        }

        return parent::getTypeOfChildProperty($targetType, $propertyName, $configuration);
    }


}
