# Netlogix.AssetMetadata

This package allows you to attach multiple metadata objects to an asset.

Assets are automatically given the `Netlogix\AssetMetadata\Domain\Model\AssetMetadataAwareInterface` using an Aspect, so you can simply call `$asset->getMetadata('myMetadata')`.

## Configuration / Usage

1. Create a new Model with your desired properties that extends `Netlogix\AssetMetadata\Domain\Model\AssetMetadata`
2. Configure the metadata:
```yaml
Netlogix:
  AssetMetadata:
    metadata:
      'mymetadata':
        label: 'My Metadata'
        # Class that implements this Metadata. Must extend AssetMetadata
        className: 'My\Metadata\Domain\Model\MyMetadata'
        # Partial root path that contains the edit partial
        editPartialRootPath: 'resource://My.Metadata/Private/Partials'
        # Partial File to render in Asset Edit View
        # Path must be Package/Resources/Private/Partials/AssetMetadata/<editPartialName>.html
        editPartialName: 'MyMetadata'
```
3. Create the edit partial:
```html
<label for="mymetadata-fieldA">Field A</label>
<f:form.textfield property="{metadataPropertyPath}.fieldA" id="mymetadata-fieldA" placeholder="Foo" type="text"/>
```
4. Set the metadata `$asset->setMetadata('mymetadata', new MyMetadata('mymetadata', $asset))`
5. Get the metadata `$asset->getMetadata('mymetadata')`
