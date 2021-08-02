<?php
namespace Netlogix\AssetMetadata\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;
use Neos\Media\Domain\Model\Asset;
use Netlogix\AssetMetadata\Domain\Model\AssetMetadata;

/**
 * @Flow\Scope("singleton")
 */
class AssetMetadataRepository extends Repository
{

    /**
     * @var array<string, string>
     */
    protected $defaultOrderings = [
        'asset.title' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * @param Asset $asset
     * @return QueryResultInterface<AssetMetadata>
     */
    public function findByAsset(Asset $asset): QueryResultInterface
    {
        $query = $this->createQuery();

        $query->matching($query->equals('asset', $asset));

        return $query->execute();
    }

    /**
     * @param array $filter<string, mixed>
     * @return QueryResultInterface<AssetMetadata>
     * @throws InvalidQueryException
     */
    public function findByFilter(array $filter = [])
    {
        $query = $this->createQuery();

        $constraints = [];
        foreach ($filter as $propertyName => $propertyValue) {
            if (is_array($propertyValue) && array_key_exists('conditionType', $propertyValue)) {
                $currentConstraint = null;
                $conditionType = $propertyValue['conditionType'];
                $isNotConstraint = strpos($conditionType, '!') === 0;

                if ($isNotConstraint) {
                    $conditionType = substr($conditionType, 1);
                }
                switch ($conditionType) {
                    case 'in':
                        $currentConstraint = $query->in($propertyName, $propertyValue['value']);
                        break;
                    case 'like':
                        $currentConstraint = $query->like($propertyName, $propertyValue['value']);
                        break;
                    case 'contains':
                        $currentConstraint = $query->contains($propertyName, $propertyValue['value']);
                        break;
                }

                if ($currentConstraint) {
                    if ($isNotConstraint) {
                        $constraints[] = $query->logicalNot($currentConstraint);
                    } else {
                        $constraints[] = $currentConstraint;
                    }
                }

            } else {
                $constraints[] = $query->equals($propertyName, $propertyValue);
            }
        }
        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
    }

    /**
     * @param Asset $asset
     * @param string $name
     * @return AssetMetadata|null
     */
    public function findOneByAssetAndName(Asset $asset, string $name): ?AssetMetadata
    {
        $query = $this->createQuery();

        $query->matching($query->logicalAnd([
            $query->equals('asset', $asset),
            $query->equals('metadataName', $name)
        ]));

        return $query->execute()->getFirst();
    }

}
