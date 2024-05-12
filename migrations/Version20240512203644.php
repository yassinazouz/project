<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512203644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
      $this->addSql('DROP TABLE livres');  $this->addSql('DROP TABLE categorie');
        
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_details');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE livres (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, auteur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, resume LONGTEXT DEFAULT NULL, editeur VARCHAR(255) NOT NULL, date_edition DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, qte INT NOT NULL, categorie_id INT DEFAULT NULL, INDEX IDX_927187A4BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, ref VARCHAR(20) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, users_id INT NOT NULL, INDEX IDX_E52FFDEE67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE orders_details (quantity INT NOT NULL, price INT NOT NULL, orders_id INT NOT NULL, livres_id INT NOT NULL, INDEX IDX_835379F1CFFE9AD6 (orders_id), INDEX IDX_835379F1EBF07F38 (livres_id), PRIMARY KEY(orders_id, livres_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A4BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1EBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A4BCF5E72D');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE67B3B43D');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1CFFE9AD6');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1EBF07F38');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE livres');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_details');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
