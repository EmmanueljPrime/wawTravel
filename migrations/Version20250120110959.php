<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120110959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE checkpoint (id INT AUTO_INCREMENT NOT NULL, road_trip_id INT NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, arrival_date DATETIME NOT NULL, departure_date DATETIME DEFAULT NULL, INDEX IDX_F00F7BEFBF41406 (road_trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE road_trip (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, cover_image VARCHAR(255) DEFAULT NULL, INDEX IDX_626235CA7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_1B80E4867E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE checkpoint ADD CONSTRAINT FK_F00F7BEFBF41406 FOREIGN KEY (road_trip_id) REFERENCES road_trip (id)');
        $this->addSql('ALTER TABLE road_trip ADD CONSTRAINT FK_626235CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checkpoint DROP FOREIGN KEY FK_F00F7BEFBF41406');
        $this->addSql('ALTER TABLE road_trip DROP FOREIGN KEY FK_626235CA7E3C61F9');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9');
        $this->addSql('DROP TABLE checkpoint');
        $this->addSql('DROP TABLE road_trip');
        $this->addSql('DROP TABLE vehicle');
    }
}
