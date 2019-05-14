<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190510150350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Created SettingsEntity';
    }

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE settings_entity (id INT AUTO_INCREMENT NOT NULL, global_refresh_time TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE device_entity CHANGE sbts_status sbts_status TINYINT(1) DEFAULT NULL, CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT NULL, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT NULL, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT NULL, CHANGE active_sw_version active_sw_version VARCHAR(255) DEFAULT NULL, CHANGE passive_sw_version passive_sw_version VARCHAR(255) DEFAULT NULL, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT NULL, CHANGE lte_state lte_state VARCHAR(255) DEFAULT NULL, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT NULL, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT NULL, CHANGE detected_hardware detected_hardware JSON DEFAULT NULL, CHANGE connected_rf_modules connected_rf_modules JSON DEFAULT NULL, CHANGE active_alarms active_alarms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE ip_addresses ip_addresses JSON DEFAULT NULL, CHANGE controllers controllers JSON DEFAULT NULL, CHANGE synchronization_source synchronization_source VARCHAR(255) DEFAULT NULL, CHANGE synchronization_status synchronization_status VARCHAR(255) DEFAULT NULL, CHANGE timesources timesources JSON DEFAULT NULL, CHANGE refresh_time refresh_time TIME DEFAULT NULL');
    }

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE settings_entity');
        $this->addSql('ALTER TABLE device_entity CHANGE sbts_status sbts_status TINYINT(1) DEFAULT \'NULL\', CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT \'NULL\', CHANGE active_sw_version active_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE passive_sw_version passive_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT \'NULL\', CHANGE lte_state lte_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE detected_hardware detected_hardware LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE connected_rf_modules connected_rf_modules LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE active_alarms active_alarms LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE ip_addresses ip_addresses LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE controllers controllers LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE synchronization_source synchronization_source VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE synchronization_status synchronization_status VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE timesources timesources LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE refresh_time refresh_time TIME DEFAULT \'NULL\'');
    }
}
