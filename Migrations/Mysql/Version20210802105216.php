<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbortMigrationException;

/**
 * Update asset foreign key
 */
class Version20210802105216 extends AbstractMigration
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
     * @throws AbortMigrationException
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata DROP FOREIGN KEY FK_64F0FD782AF5A5C');
        $this->addSql('DROP INDEX idx_64f0fd782af5a5c ON netlogix_assetmetadata_domain_model_assetmetadata');
        $this->addSql('CREATE INDEX IDX_C1A8E8C52AF5A5C ON netlogix_assetmetadata_domain_model_assetmetadata (asset)');
        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata ADD CONSTRAINT FK_64F0FD782AF5A5C FOREIGN KEY (asset) REFERENCES neos_media_domain_model_asset (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     * @throws AbortMigrationException
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata DROP FOREIGN KEY FK_C1A8E8C52AF5A5C');
        $this->addSql('DROP INDEX idx_c1a8e8c52af5a5c ON netlogix_assetmetadata_domain_model_assetmetadata');
        $this->addSql('CREATE INDEX IDX_64F0FD782AF5A5C ON netlogix_assetmetadata_domain_model_assetmetadata (asset)');
        $this->addSql('ALTER TABLE netlogix_assetmetadata_domain_model_assetmetadata ADD CONSTRAINT FK_C1A8E8C52AF5A5C FOREIGN KEY (asset) REFERENCES neos_media_domain_model_asset (persistence_object_identifier)');
    }
}
