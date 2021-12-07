<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211207205902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statistic (id INT AUTO_INCREMENT NOT NULL, comment_count INT NOT NULL, dislike_count INT NOT NULL, favorite_count INT NOT NULL, like_count INT NOT NULL, view_count INT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_video (tag_id INT NOT NULL, video_id INT NOT NULL, INDEX IDX_5E2BC32ABAD26311 (tag_id), INDEX IDX_5E2BC32A29C1004E (video_id), PRIMARY KEY(tag_id, video_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_video ADD CONSTRAINT FK_5E2BC32ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_video ADD CONSTRAINT FK_5E2BC32A29C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video ADD created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_video DROP FOREIGN KEY FK_5E2BC32ABAD26311');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_video');
        $this->addSql('ALTER TABLE video DROP created_at');
    }
}
