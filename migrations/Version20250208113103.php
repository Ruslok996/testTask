<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208113103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id SERIAL NOT NULL, balance DOUBLE PRECISION NOT NULL, is_active BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cash_account (id INT NOT NULL, cash_register_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_23DA44F8A917CC69 ON cash_account (cash_register_id)');
        $this->addSql('CREATE TABLE cash_register (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D7AB1D95E237E06 ON cash_register (name)');
        $this->addSql('CREATE TABLE transaction (id SERIAL NOT NULL, source_account_id INT DEFAULT NULL, destination_account_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, is_canceled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1E7DF2E9E ON transaction (source_account_id)');
        $this->addSql('CREATE INDEX IDX_723705D1C652C408 ON transaction (destination_account_id)');
        $this->addSql('CREATE INDEX IDX_723705D1B03A8386 ON transaction (created_by_id)');
        $this->addSql('CREATE INDEX IDX_723705D1896DBBDE ON transaction (updated_by_id)');
        $this->addSql('COMMENT ON COLUMN transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN transaction.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) DEFAULT NULL, middle_name VARCHAR(50) DEFAULT NULL, phones JSON DEFAULT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE user_account (id INT NOT NULL, owner_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_253B48AE7E3C61F9 ON user_account (owner_id)');
        $this->addSql('ALTER TABLE cash_account ADD CONSTRAINT FK_23DA44F8A917CC69 FOREIGN KEY (cash_register_id) REFERENCES cash_register (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cash_account ADD CONSTRAINT FK_23DA44F8BF396750 FOREIGN KEY (id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1E7DF2E9E FOREIGN KEY (source_account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C652C408 FOREIGN KEY (destination_account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AEBF396750 FOREIGN KEY (id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cash_account DROP CONSTRAINT FK_23DA44F8A917CC69');
        $this->addSql('ALTER TABLE cash_account DROP CONSTRAINT FK_23DA44F8BF396750');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1E7DF2E9E');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1C652C408');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1B03A8386');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1896DBBDE');
        $this->addSql('ALTER TABLE user_account DROP CONSTRAINT FK_253B48AE7E3C61F9');
        $this->addSql('ALTER TABLE user_account DROP CONSTRAINT FK_253B48AEBF396750');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE cash_account');
        $this->addSql('DROP TABLE cash_register');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_account');
    }
}
