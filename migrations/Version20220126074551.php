<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126074551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alumno_asignatura (alumno_id INT NOT NULL, asignatura_id INT NOT NULL, INDEX IDX_996F1A27FC28E5EE (alumno_id), INDEX IDX_996F1A27C5C70C5B (asignatura_id), PRIMARY KEY(alumno_id, asignatura_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alumno_asignatura ADD CONSTRAINT FK_996F1A27FC28E5EE FOREIGN KEY (alumno_id) REFERENCES alumno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alumno_asignatura ADD CONSTRAINT FK_996F1A27C5C70C5B FOREIGN KEY (asignatura_id) REFERENCES asignatura (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE alumno_asignatura');
    }
}
