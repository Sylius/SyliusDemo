<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190123104431 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_api_access_token DROP FOREIGN KEY FK_7D83AA7F19EB6921');
        $this->addSql('ALTER TABLE sylius_api_auth_code DROP FOREIGN KEY FK_C840417919EB6921');
        $this->addSql('ALTER TABLE sylius_api_refresh_token DROP FOREIGN KEY FK_4457852519EB6921');
        $this->addSql('ALTER TABLE sylius_payment DROP FOREIGN KEY FK_D9191BD47048FD0F');
        $this->addSql('CREATE TABLE sylius_product_variant_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_8DC18EDC2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_variant_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_association_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_4F618E52C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_association_type_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_address_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_image_product_variants (image_id INT NOT NULL, variant_id INT NOT NULL, INDEX IDX_8FFDAE8D3DA5256D (image_id), INDEX IDX_8FFDAE8D3B69A9AF (variant_id), PRIMARY KEY(image_id, variant_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_admin_api_access_token (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2AA4915D5F37A13B (token), INDEX IDX_2AA4915D19EB6921 (client_id), INDEX IDX_2AA4915DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_admin_api_refresh_token (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_9160E3FA5F37A13B (token), INDEX IDX_9160E3FA19EB6921 (client_id), INDEX IDX_9160E3FAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_admin_api_client (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_admin_api_auth_code (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri LONGTEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E366D8485F37A13B (token), INDEX IDX_E366D84819EB6921 (client_id), INDEX IDX_E366D848A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product_variant_translation ADD CONSTRAINT FK_8DC18EDC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation ADD CONSTRAINT FK_4F618E52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_association_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_image_product_variants ADD CONSTRAINT FK_8FFDAE8D3DA5256D FOREIGN KEY (image_id) REFERENCES sylius_product_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_image_product_variants ADD CONSTRAINT FK_8FFDAE8D3B69A9AF FOREIGN KEY (variant_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_admin_api_access_token ADD CONSTRAINT FK_2AA4915D19EB6921 FOREIGN KEY (client_id) REFERENCES sylius_admin_api_client (id)');
        $this->addSql('ALTER TABLE sylius_admin_api_access_token ADD CONSTRAINT FK_2AA4915DA76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE sylius_admin_api_refresh_token ADD CONSTRAINT FK_9160E3FA19EB6921 FOREIGN KEY (client_id) REFERENCES sylius_admin_api_client (id)');
        $this->addSql('ALTER TABLE sylius_admin_api_refresh_token ADD CONSTRAINT FK_9160E3FAA76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE sylius_admin_api_auth_code ADD CONSTRAINT FK_E366D84819EB6921 FOREIGN KEY (client_id) REFERENCES sylius_admin_api_client (id)');
        $this->addSql('ALTER TABLE sylius_admin_api_auth_code ADD CONSTRAINT FK_E366D848A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('DROP TABLE sylius_api_access_token');
        $this->addSql('DROP TABLE sylius_api_auth_code');
        $this->addSql('DROP TABLE sylius_api_client');
        $this->addSql('DROP TABLE sylius_api_refresh_token');
        $this->addSql('DROP TABLE sylius_credit_card');
        $this->addSql('ALTER TABLE sylius_exchange_rate ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_locale DROP enabled');
        $this->addSql('ALTER TABLE sylius_product_option ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_association_type DROP name');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD locale_code VARCHAR(255) NOT NULL, ADD json_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE sylius_taxon ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD archived_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order_sequence ADD version INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE sylius_order CHANGE notes notes LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_review DROP FOREIGN KEY FK_C7056A99F675F31B');
        $this->addSql('ALTER TABLE sylius_product_review CHANGE author_id author_id INT NOT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_review ADD CONSTRAINT FK_C7056A99F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_order_item ADD product_name VARCHAR(255) DEFAULT NULL, ADD variant_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_105A908989D9B62 ON sylius_product_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_105A9084180C698989D9B62 ON sylius_product_translation (locale, slug)');
        $this->addSql('DROP INDEX product_image_code_idx ON sylius_product_image');
        $this->addSql('ALTER TABLE sylius_product_image ADD type VARCHAR(255) DEFAULT NULL, DROP code');
        $this->addSql('ALTER TABLE sylius_product_variant ADD position INT NOT NULL, ADD version INT DEFAULT 1 NOT NULL, ADD shipping_required TINYINT(1) NOT NULL, DROP available_on, DROP available_until, DROP name');
        $this->addSql('ALTER TABLE sylius_channel ADD contact_email VARCHAR(255) DEFAULT NULL, ADD skipping_shipping_step_allowed TINYINT(1) NOT NULL, ADD skipping_payment_step_allowed TINYINT(1) NOT NULL, ADD account_verification_required TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX IDX_16C8119EE551C011 ON sylius_channel (hostname)');
        $this->addSql('DROP INDEX taxon_image_code_idx ON sylius_taxon_image');
        $this->addSql('ALTER TABLE sylius_taxon_image ADD type VARCHAR(255) DEFAULT NULL, DROP code');
        $this->addSql('ALTER TABLE sylius_product DROP available_on, DROP available_until');
        $this->addSql('CREATE UNIQUE INDEX product_taxon_idx ON sylius_product_taxon (product_id, taxon_id)');
        $this->addSql('ALTER TABLE sylius_channel_pricing DROP FOREIGN KEY FK_7801820C72F5A1AA');
        $this->addSql('DROP INDEX IDX_7801820C72F5A1AA ON sylius_channel_pricing');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD original_price INT DEFAULT NULL, ADD channel_code VARCHAR(255) NOT NULL, DROP channel_id');
        $this->addSql('CREATE UNIQUE INDEX product_variant_channel_idx ON sylius_channel_pricing (product_variant_id, channel_code)');
        $this->addSql('ALTER TABLE sylius_payment_method ADD gateway_config_id INT DEFAULT NULL, DROP gateway');
        $this->addSql('ALTER TABLE sylius_payment_method ADD CONSTRAINT FK_A75B0B0DF23D6140 FOREIGN KEY (gateway_config_id) REFERENCES sylius_gateway_config (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A75B0B0DF23D6140 ON sylius_payment_method (gateway_config_id)');
        $this->addSql('ALTER TABLE sylius_payment DROP FOREIGN KEY FK_D9191BD419883967');
        $this->addSql('DROP INDEX IDX_D9191BD47048FD0F ON sylius_payment');
        $this->addSql('ALTER TABLE sylius_payment DROP credit_card_id');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD419883967 FOREIGN KEY (method_id) REFERENCES sylius_payment_method (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_admin_api_access_token DROP FOREIGN KEY FK_2AA4915D19EB6921');
        $this->addSql('ALTER TABLE sylius_admin_api_refresh_token DROP FOREIGN KEY FK_9160E3FA19EB6921');
        $this->addSql('ALTER TABLE sylius_admin_api_auth_code DROP FOREIGN KEY FK_E366D84819EB6921');
        $this->addSql('CREATE TABLE sylius_api_access_token (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_7D83AA7F19EB6921 (client_id), INDEX IDX_7D83AA7FA76ED395 (user_id), UNIQUE INDEX UNIQ_7D83AA7F5F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_api_auth_code (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, expires_at INT DEFAULT NULL, redirect_uri LONGTEXT NOT NULL COLLATE utf8_unicode_ci, scope VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_C840417919EB6921 (client_id), INDEX IDX_C8404179A76ED395 (user_id), UNIQUE INDEX UNIQ_C84041795F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_api_client (id INT AUTO_INCREMENT NOT NULL, allowed_grant_types LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', random_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, redirect_uris LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_api_refresh_token (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, token VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_4457852519EB6921 (client_id), INDEX IDX_44578525A76ED395 (user_id), UNIQUE INDEX UNIQ_445785255F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_credit_card (id INT AUTO_INCREMENT NOT NULL, cardholder_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, expiry_month INT DEFAULT NULL, expiry_year INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, security_code VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, type VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_api_access_token ADD CONSTRAINT FK_7D83AA7F19EB6921 FOREIGN KEY (client_id) REFERENCES sylius_api_client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sylius_api_access_token ADD CONSTRAINT FK_7D83AA7FA76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sylius_api_auth_code ADD CONSTRAINT FK_C840417919EB6921 FOREIGN KEY (client_id) REFERENCES sylius_api_client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sylius_api_auth_code ADD CONSTRAINT FK_C8404179A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sylius_api_refresh_token ADD CONSTRAINT FK_4457852519EB6921 FOREIGN KEY (client_id) REFERENCES sylius_api_client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sylius_api_refresh_token ADD CONSTRAINT FK_44578525A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE sylius_product_variant_translation');
        $this->addSql('DROP TABLE sylius_product_association_type_translation');
        $this->addSql('DROP TABLE sylius_address_log_entries');
        $this->addSql('DROP TABLE sylius_product_image_product_variants');
        $this->addSql('DROP TABLE sylius_admin_api_access_token');
        $this->addSql('DROP TABLE sylius_admin_api_refresh_token');
        $this->addSql('DROP TABLE sylius_admin_api_client');
        $this->addSql('DROP TABLE sylius_admin_api_auth_code');
        $this->addSql('DROP INDEX IDX_16C8119EE551C011 ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel DROP contact_email, DROP skipping_shipping_step_allowed, DROP skipping_payment_step_allowed, DROP account_verification_required');
        $this->addSql('DROP INDEX product_variant_channel_idx ON sylius_channel_pricing');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD channel_id INT NOT NULL, DROP original_price, DROP channel_code');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD CONSTRAINT FK_7801820C72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7801820C72F5A1AA ON sylius_channel_pricing (channel_id)');
        $this->addSql('ALTER TABLE sylius_exchange_rate DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE sylius_locale ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sylius_order CHANGE notes notes VARCHAR(1000) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_order_item DROP product_name, DROP variant_name');
        $this->addSql('ALTER TABLE sylius_order_sequence DROP version');
        $this->addSql('ALTER TABLE sylius_payment DROP FOREIGN KEY FK_D9191BD419883967');
        $this->addSql('ALTER TABLE sylius_payment ADD credit_card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD47048FD0F FOREIGN KEY (credit_card_id) REFERENCES sylius_credit_card (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_payment ADD CONSTRAINT FK_D9191BD419883967 FOREIGN KEY (method_id) REFERENCES sylius_payment_method (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D9191BD47048FD0F ON sylius_payment (credit_card_id)');
        $this->addSql('ALTER TABLE sylius_payment_method DROP FOREIGN KEY FK_A75B0B0DF23D6140');
        $this->addSql('DROP INDEX IDX_A75B0B0DF23D6140 ON sylius_payment_method');
        $this->addSql('ALTER TABLE sylius_payment_method ADD gateway VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP gateway_config_id');
        $this->addSql('ALTER TABLE sylius_product ADD available_on DATETIME DEFAULT NULL, ADD available_until DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_association_type ADD name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP locale_code, DROP json_value');
        $this->addSql('ALTER TABLE sylius_product_image ADD code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP type');
        $this->addSql('CREATE UNIQUE INDEX product_image_code_idx ON sylius_product_image (owner_id, code)');
        $this->addSql('ALTER TABLE sylius_product_option DROP position');
        $this->addSql('ALTER TABLE sylius_product_review DROP FOREIGN KEY FK_C7056A99F675F31B');
        $this->addSql('ALTER TABLE sylius_product_review CHANGE author_id author_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_review ADD CONSTRAINT FK_C7056A99F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX product_taxon_idx ON sylius_product_taxon');
        $this->addSql('DROP INDEX UNIQ_105A9084180C698989D9B62 ON sylius_product_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_105A908989D9B62 ON sylius_product_translation (slug)');
        $this->addSql('ALTER TABLE sylius_product_variant ADD available_on DATETIME DEFAULT NULL, ADD available_until DATETIME DEFAULT NULL, ADD name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP position, DROP version, DROP shipping_required');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP archived_at');
        $this->addSql('ALTER TABLE sylius_taxon DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE sylius_taxon_image ADD code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP type');
        $this->addSql('CREATE UNIQUE INDEX taxon_image_code_idx ON sylius_taxon_image (owner_id, code)');
    }
}
