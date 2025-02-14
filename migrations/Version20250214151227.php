<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214151227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), UNIQUE INDEX UNIQ_1C52F958989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, hex_code VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_7CBE75955E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, url VARCHAR(255) NOT NULL, alt_html VARCHAR(255) NOT NULL, position INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_color (product_variant_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_3C31CB46A80EF684 (product_variant_id), INDEX IDX_3C31CB467ADA1FB5 (color_id), PRIMARY KEY(product_variant_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_material (product_variant_id INT NOT NULL, material_id INT NOT NULL, INDEX IDX_6DE250E1A80EF684 (product_variant_id), INDEX IDX_6DE250E1E308AC6F (material_id), PRIMARY KEY(product_variant_id, material_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_F7C0246A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variant_color ADD CONSTRAINT FK_3C31CB46A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_color ADD CONSTRAINT FK_3C31CB467ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_material ADD CONSTRAINT FK_6DE250E1A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_material ADD CONSTRAINT FK_6DE250E1E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD44F5D008 ON product (brand_id)');
        $this->addSql('ALTER TABLE product_variant ADD size_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
        $this->addSql('CREATE INDEX IDX_209AA41D498DA827 ON product_variant (size_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D498DA827');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_variant_color DROP FOREIGN KEY FK_3C31CB46A80EF684');
        $this->addSql('ALTER TABLE product_variant_color DROP FOREIGN KEY FK_3C31CB467ADA1FB5');
        $this->addSql('ALTER TABLE product_variant_material DROP FOREIGN KEY FK_6DE250E1A80EF684');
        $this->addSql('ALTER TABLE product_variant_material DROP FOREIGN KEY FK_6DE250E1E308AC6F');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_variant_color');
        $this->addSql('DROP TABLE product_variant_material');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP INDEX IDX_D34A04AD44F5D008 ON product');
        $this->addSql('ALTER TABLE product DROP brand_id');
        $this->addSql('DROP INDEX IDX_209AA41D498DA827 ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP size_id');
    }
}
