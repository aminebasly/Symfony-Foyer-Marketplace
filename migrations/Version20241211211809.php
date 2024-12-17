<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211211809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine ADD est_reserve TINYINT(1) NOT NULL, ADD heure_debut INT DEFAULT NULL, ADD duree_reserve INT DEFAULT NULL, DROP statut_machine, CHANGE id_machine laverie_id INT NOT NULL');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8497C840DF FOREIGN KEY (laverie_id) REFERENCES laverie (id)');
        $this->addSql('CREATE INDEX IDX_1505DF8497C840DF ON machine (laverie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF8497C840DF');
        $this->addSql('DROP INDEX IDX_1505DF8497C840DF ON machine');
        $this->addSql('ALTER TABLE machine ADD statut_machine VARCHAR(255) NOT NULL, DROP est_reserve, DROP heure_debut, DROP duree_reserve, CHANGE laverie_id id_machine INT NOT NULL');
    }
}
