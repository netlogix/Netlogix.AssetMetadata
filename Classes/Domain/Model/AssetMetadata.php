<?php
namespace Netlogix\AssetMetadata\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Asset;

/**
 * @Flow\Entity
 * @ORM\InheritanceType("JOINED")
 */
abstract class AssetMetadata
{

    /**
     * @var string
     */
    protected $metadataName;

    /**
     * @var Asset
     * @ORM\ManyToOne
     */
    protected $asset;

    /**
     * @param string $metadataName
     * @param Asset $asset
     */
    public function __construct(string $metadataName, Asset $asset)
    {
        $this->metadataName = $metadataName;
        $this->asset = $asset;
    }

    /**
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * @return string
     */
    public function getMetadataName(): string
    {
        return $this->metadataName;
    }

}
