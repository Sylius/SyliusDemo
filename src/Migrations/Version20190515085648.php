<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515085648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_channel DROP FOREIGN KEY FK_16C8119E5CDB2AEB');
        $this->addSql('DROP TABLE sylius_invoicing_plugin_shop_billing_data');
        $this->addSql('ALTER TABLE sylius_invoicing_plugin_invoice CHANGE channel_code channel_code VARCHAR(255) NOT NULL, CHANGE channel_name channel_name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX IDX_6196A1F9A393D2FB43625D9F ON sylius_order (state, updated_at)');
        $this->addSql('ALTER TABLE sylius_promotion_coupon ADD reusable_from_cancelled_orders TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_16C8119E5CDB2AEB ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel DROP billing_data_id, DROP taxId');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_invoicing_plugin_shop_billing_data (id INT AUTO_INCREMENT NOT NULL, company VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, tax_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, street VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, postcode VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, country_code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sylius_channel ADD billing_data_id INT DEFAULT NULL, ADD taxId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_channel ADD CONSTRAINT FK_16C8119E5CDB2AEB FOREIGN KEY (billing_data_id) REFERENCES sylius_invoicing_plugin_shop_billing_data (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16C8119E5CDB2AEB ON sylius_channel (billing_data_id)');
        $this->addSql('ALTER TABLE sylius_invoicing_plugin_invoice CHANGE channel_code channel_code VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE channel_name channel_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX IDX_6196A1F9A393D2FB43625D9F ON sylius_order');
        $this->addSql('ALTER TABLE sylius_promotion_coupon DROP reusable_from_cancelled_orders');
    }
}
