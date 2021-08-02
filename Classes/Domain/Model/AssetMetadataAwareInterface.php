<?php
namespace Netlogix\AssetMetadata\Domain\Model;

interface AssetMetadataAwareInterface
{

    /**
     * @param string $metadataName
     * @return AssetMetadata
     */
    function getMetadata($metadataName = null);

    /**
     * @param array $metadata
     * @return mixed
     */
    function setMetadata($metadata);

}
