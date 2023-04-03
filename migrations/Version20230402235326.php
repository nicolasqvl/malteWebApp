<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230402235326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kit ADD unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE kit ADD CONSTRAINT FK_58949818F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('CREATE INDEX IDX_58949818F8BD700D ON kit (unit_id)');
        $this->addSql('ALTER TABLE kit_product ADD unit_id INT NOT NULL, ADD product_id INT NOT NULL, ADD kit_id INT NOT NULL');
        $this->addSql('ALTER TABLE kit_product ADD CONSTRAINT FK_5AEF274AF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE kit_product ADD CONSTRAINT FK_5AEF274A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE kit_product ADD CONSTRAINT FK_5AEF274A3A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id)');
        $this->addSql('CREATE INDEX IDX_5AEF274AF8BD700D ON kit_product (unit_id)');
        $this->addSql('CREATE INDEX IDX_5AEF274A4584665A ON kit_product (product_id)');
        $this->addSql('CREATE INDEX IDX_5AEF274A3A8E60EF ON kit_product (kit_id)');
        $this->addSql('ALTER TABLE `order` ADD kit_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993983A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id)');
        $this->addSql('CREATE INDEX IDX_F52993983A8E60EF ON `order` (kit_id)');
        $this->addSql('ALTER TABLE order_detail ADD product_id INT NOT NULL, ADD order_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46F65E9B0F FOREIGN KEY (order_product_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_ED896F464584665A ON order_detail (product_id)');
        $this->addSql('CREATE INDEX IDX_ED896F46F65E9B0F ON order_detail (order_product_id)');
        $this->addSql('ALTER TABLE product ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('ALTER TABLE team ADD unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FF8BD700D ON team (unit_id)');
        $this->addSql('ALTER TABLE user ADD unit_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F8BD700D ON user (unit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE kit DROP FOREIGN KEY FK_58949818F8BD700D');
        $this->addSql('DROP INDEX IDX_58949818F8BD700D ON kit');
        $this->addSql('ALTER TABLE kit DROP unit_id');
        $this->addSql('ALTER TABLE kit_product DROP FOREIGN KEY FK_5AEF274AF8BD700D');
        $this->addSql('ALTER TABLE kit_product DROP FOREIGN KEY FK_5AEF274A4584665A');
        $this->addSql('ALTER TABLE kit_product DROP FOREIGN KEY FK_5AEF274A3A8E60EF');
        $this->addSql('DROP INDEX IDX_5AEF274AF8BD700D ON kit_product');
        $this->addSql('DROP INDEX IDX_5AEF274A4584665A ON kit_product');
        $this->addSql('DROP INDEX IDX_5AEF274A3A8E60EF ON kit_product');
        $this->addSql('ALTER TABLE kit_product DROP unit_id, DROP product_id, DROP kit_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993983A8E60EF');
        $this->addSql('DROP INDEX IDX_F52993983A8E60EF ON `order`');
        $this->addSql('ALTER TABLE `order` DROP kit_id');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F464584665A');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46F65E9B0F');
        $this->addSql('DROP INDEX IDX_ED896F464584665A ON order_detail');
        $this->addSql('DROP INDEX IDX_ED896F46F65E9B0F ON order_detail');
        $this->addSql('ALTER TABLE order_detail DROP product_id, DROP order_product_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product DROP category_id');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FF8BD700D');
        $this->addSql('DROP INDEX IDX_C4E0A61FF8BD700D ON team');
        $this->addSql('ALTER TABLE team DROP unit_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F8BD700D');
        $this->addSql('DROP INDEX IDX_8D93D649F8BD700D ON user');
        $this->addSql('ALTER TABLE user DROP unit_id');
    }
}
