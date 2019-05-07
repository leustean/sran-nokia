<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190507214057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE device_entity ADD ip VARCHAR(16) NOT NULL, ADD port INT NOT NULL, ADD user VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD refresh_time TIME NOT NULL, CHANGE sbts_status sbts_status TINYINT(1) DEFAULT NULL, CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT NULL, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT NULL, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT NULL, CHANGE active_sw_version active_sw_version VARCHAR(255) DEFAULT NULL, CHANGE passive_sw_version passive_sw_version VARCHAR(255) DEFAULT NULL, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT NULL, CHANGE lte_state lte_state VARCHAR(255) DEFAULT NULL, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT NULL, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT NULL, CHANGE detected_hardware detected_hardware JSON DEFAULT NULL, CHANGE connected_rf_modules connected_rf_modules JSON DEFAULT NULL, CHANGE active_alarms active_alarms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE ip_addresses ip_addresses JSON DEFAULT NULL, CHANGE controllers controllers JSON DEFAULT NULL, CHANGE synchronization_source synchronization_source VARCHAR(255) DEFAULT NULL, CHANGE synchronization_status synchronization_status VARCHAR(255) DEFAULT NULL, CHANGE timesources timesources JSON DEFAULT NULL');
        $this->addSql('CREATE INDEX sbts_id_idx ON device_entity (sbts_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX sbts_id_idx ON device_entity');
        $this->addSql('ALTER TABLE device_entity DROP ip, DROP port, DROP user, DROP password, DROP refresh_time, CHANGE sbts_status sbts_status TINYINT(1) DEFAULT \'NULL\', CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT \'NULL\', CHANGE active_sw_version active_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE passive_sw_version passive_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT \'NULL\', CHANGE lte_state lte_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE detected_hardware detected_hardware LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE connected_rf_modules connected_rf_modules LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE active_alarms active_alarms LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE ip_addresses ip_addresses LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE controllers controllers LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE synchronization_source synchronization_source VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE synchronization_status synchronization_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE timesources timesources LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin');
    }
}
