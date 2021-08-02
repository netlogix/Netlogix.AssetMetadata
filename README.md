# Netlogix.AssetMetadata

This package allows you to attach multiple metadata objects to an asset.

Assets are automatically given the `Netlogix\AssetMetadata\Domain\Model\AssetMetadataAwareInterface` using an Aspect, so you can simply call `$asset->getMetadata('myMetadata')`.

## Configuration / Usage

1. Create a new Model with your desired properties that extends `Netlogix\AssetMetadata\Domain\Model\AssetMetadata`
2. Configure the class mapping:
```yaml
Netlogix:
  AssetMetadata:
    nameToClassNameMap:
      'myMetadata': 'My\Metadata\Domain\Model\MyMetadata'
```
3. Set the metadata `$asset->setMetadata('myMetadata', new MyMetadata('myMetadata', $asset))`
4. Get the metadata `$asset->getMetadata('myMetadata')`
