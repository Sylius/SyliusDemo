<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200220074850 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bitbag_cms_block_taxonomies (block_id INT NOT NULL, taxon_id INT NOT NULL, INDEX IDX_10C3E429E9ED820C (block_id), INDEX IDX_10C3E429DE13F470 (taxon_id), PRIMARY KEY(block_id, taxon_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bitbag_cms_block_taxonomies ADD CONSTRAINT FK_10C3E429E9ED820C FOREIGN KEY (block_id) REFERENCES bitbag_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_block_taxonomies ADD CONSTRAINT FK_10C3E429DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_block_sections DROP FOREIGN KEY FK_5C95115DE9ED820C');
        $this->addSql('ALTER TABLE bitbag_cms_block_sections ADD CONSTRAINT FK_5C95115DE9ED820C FOREIGN KEY (block_id) REFERENCES bitbag_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_block_products DROP FOREIGN KEY FK_C4B9089FE9ED820C');
        $this->addSql('ALTER TABLE bitbag_cms_block_products ADD CONSTRAINT FK_C4B9089FE9ED820C FOREIGN KEY (block_id) REFERENCES bitbag_cms_block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_media_sections DROP FOREIGN KEY FK_98BC300EA9FDD75');
        $this->addSql('ALTER TABLE bitbag_cms_media_sections ADD CONSTRAINT FK_98BC300EA9FDD75 FOREIGN KEY (media_id) REFERENCES bitbag_cms_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_media_products DROP FOREIGN KEY FK_91A7DAC2EA9FDD75');
        $this->addSql('ALTER TABLE bitbag_cms_media_products ADD CONSTRAINT FK_91A7DAC2EA9FDD75 FOREIGN KEY (media_id) REFERENCES bitbag_cms_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bitbag_cms_page_translation ADD title VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bitbag_cms_block_taxonomies');
        $this->addSql('ALTER TABLE bitbag_cms_block_products DROP FOREIGN KEY FK_C4B9089FE9ED820C');
        $this->addSql('ALTER TABLE bitbag_cms_block_products ADD CONSTRAINT FK_C4B9089FE9ED820C FOREIGN KEY (block_id) REFERENCES bitbag_cms_block (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bitbag_cms_block_sections DROP FOREIGN KEY FK_5C95115DE9ED820C');
        $this->addSql('ALTER TABLE bitbag_cms_block_sections ADD CONSTRAINT FK_5C95115DE9ED820C FOREIGN KEY (block_id) REFERENCES bitbag_cms_block (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bitbag_cms_media_products DROP FOREIGN KEY FK_91A7DAC2EA9FDD75');
        $this->addSql('ALTER TABLE bitbag_cms_media_products ADD CONSTRAINT FK_91A7DAC2EA9FDD75 FOREIGN KEY (media_id) REFERENCES bitbag_cms_media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bitbag_cms_media_sections DROP FOREIGN KEY FK_98BC300EA9FDD75');
        $this->addSql('ALTER TABLE bitbag_cms_media_sections ADD CONSTRAINT FK_98BC300EA9FDD75 FOREIGN KEY (media_id) REFERENCES bitbag_cms_media (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE bitbag_cms_page_translation DROP title');
    }
}
