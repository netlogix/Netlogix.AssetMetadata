<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add AssetMetadata Table
 */
final class Version20250513075736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE netlogix_assetmetadata_domain_model_assetmetadata (persistence_object_identifier VARCHAR(40) NOT NULL, asset VARCHAR(40) DEFAULT NULL, metadataname VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier))');
        $this->addSql('CREATE INDEX IDX_C1A8E8C52AF5A5C ON netlogix_assetmetadata_domain_model_assetmetadata (asset)');
        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata ADD CONSTRAINT FK_C1A8E8C52AF5A5C FOREIGN KEY (asset) REFERENCES neos_media_domain_model_asset (persistence_object_identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE netlogix_assetmetadata_domain_model_assetmetadata');
    }
}
