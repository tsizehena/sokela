<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505192839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE proverb_tag (proverb_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(proverb_id, tag_id), CONSTRAINT FK_9DCCFA189EE15F57 FOREIGN KEY (proverb_id) REFERENCES proverb (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9DCCFA18BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DCCFA189EE15F57 ON proverb_tag (proverb_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DCCFA18BAD26311 ON proverb_tag (tag_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__proverb AS SELECT id, content FROM proverb
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE proverb
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE proverb (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, topic_id INTEGER NOT NULL, content CLOB NOT NULL, CONSTRAINT FK_9271AE881F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO proverb (id, content) SELECT id, content FROM __temp__proverb
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__proverb
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9271AE881F55203D ON proverb (topic_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE proverb_tag
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__proverb AS SELECT id, content FROM proverb
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE proverb
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE proverb (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO proverb (id, content) SELECT id, content FROM __temp__proverb
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__proverb
        SQL);
    }
}
