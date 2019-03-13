<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180921110751 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_rbac_administration_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, permissions JSON NOT NULL, UNIQUE INDEX UNIQ_3333A12E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_admin_user ADD administrationRole_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_admin_user ADD CONSTRAINT FK_88D5CC4D6557905D FOREIGN KEY (administrationRole_id) REFERENCES sylius_rbac_administration_role (id)');
        $this->addSql('CREATE INDEX IDX_88D5CC4D6557905D ON sylius_admin_user (administrationRole_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_admin_user DROP FOREIGN KEY FK_88D5CC4D6557905D');
        $this->addSql('DROP TABLE sylius_rbac_administration_role');
        $this->addSql('DROP INDEX IDX_88D5CC4D6557905D ON sylius_admin_user');
        $this->addSql('ALTER TABLE sylius_admin_user DROP administrationRole_id');
    }
}
