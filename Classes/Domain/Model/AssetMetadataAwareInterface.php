<?php
namespace Netlogix\AssetMetadata\Domain\Model;

interface AssetMetadataAwareInterface
{

    /**
     * @param string|null $metadataName
     * @return AssetMetadata|array<string, AssetMetadata>
     */
    function getMetadata(?string $metadataName = null);

    /**
     * @param array<string, AssetMetadata> $metadata
     * @return void
     */
    function setMetadata(array $metadata): void;

}
