<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607224307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE result_quiz');
        $this->addSql('DROP TABLE result_user');
        $this->addSql('ALTER TABLE result ADD user_id INT DEFAULT NULL, ADD quiz_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('CREATE INDEX IDX_136AC113A76ED395 ON result (user_id)');
        $this->addSql('CREATE INDEX IDX_136AC113853CD175 ON result (quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE result_quiz (result_id INT NOT NULL, quiz_id INT NOT NULL, INDEX IDX_3429E1DC7A7B643 (result_id), INDEX IDX_3429E1DC853CD175 (quiz_id), PRIMARY KEY(result_id, quiz_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE result_user (result_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1DA8CD077A7B643 (result_id), INDEX IDX_1DA8CD07A76ED395 (user_id), PRIMARY KEY(result_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE result_quiz ADD CONSTRAINT FK_3429E1DC7A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result_quiz ADD CONSTRAINT FK_3429E1DC853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result_user ADD CONSTRAINT FK_1DA8CD077A7B643 FOREIGN KEY (result_id) REFERENCES result (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result_user ADD CONSTRAINT FK_1DA8CD07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A76ED395');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113853CD175');
        $this->addSql('DROP INDEX IDX_136AC113A76ED395 ON result');
        $this->addSql('DROP INDEX IDX_136AC113853CD175 ON result');
        $this->addSql('ALTER TABLE result DROP user_id, DROP quiz_id');
    }
}
