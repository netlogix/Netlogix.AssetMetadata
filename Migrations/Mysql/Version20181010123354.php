<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add AssetMetadata Table
 */
class Version20181010123354 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('CREATE TABLE netlogix_assetmetadata_domain_model_assetmetadata (persistence_object_identifier VARCHAR(40) NOT NULL, asset VARCHAR(40) DEFAULT NULL, metadataname VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, INDEX IDX_64F0FD782AF5A5C (asset), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata ADD CONSTRAINT FK_64F0FD782AF5A5C FOREIGN KEY (asset) REFERENCES neos_media_domain_model_asset (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('DROP TABLE netlogix_assetmetadata_domain_model_assetmetadata');
    }
}
