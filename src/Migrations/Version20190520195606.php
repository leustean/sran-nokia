<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190520195606 extends AbstractMigration {

	public function getDescription(): string {
		return 'Created indexes';
	}

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
	public function up(Schema $schema): void {
		// this up() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('ALTER TABLE alarm_entity CHANGE severity severity VARCHAR(255) DEFAULT NULL, CHANGE observation_time observation_time DATETIME DEFAULT NULL, CHANGE alarm_id alarm_id INT DEFAULT NULL, CHANGE fault_id fault_id INT DEFAULT NULL, CHANGE alarm_name alarm_name VARCHAR(255) DEFAULT NULL, CHANGE fault_severity fault_severity VARCHAR(255) DEFAULT NULL, CHANGE alarm_detail alarm_detail VARCHAR(255) DEFAULT NULL, CHANGE alarm_detail_number alarm_detail_number INT DEFAULT NULL, CHANGE fault_description fault_description VARCHAR(255) DEFAULT NULL');
		$this->addSql('CREATE INDEX severity ON alarm_entity (severity)');
		$this->addSql('CREATE INDEX observation_time ON alarm_entity (observation_time)');
		$this->addSql('CREATE INDEX alarm_id ON alarm_entity (alarm_id)');
		$this->addSql('CREATE INDEX fault_id ON alarm_entity (fault_id)');
		$this->addSql('CREATE INDEX alarm_name ON alarm_entity (alarm_name)');
		$this->addSql('CREATE INDEX fault_severity ON alarm_entity (fault_severity)');
		$this->addSql('CREATE INDEX alarm_detail ON alarm_entity (alarm_detail)');
		$this->addSql('CREATE INDEX alarm_detail_number ON alarm_entity (alarm_detail_number)');
		$this->addSql('CREATE INDEX fault_description ON alarm_entity (fault_description)');
		$this->addSql('ALTER TABLE device_entity DROP active_sw_version, DROP passive_sw_version, DROP ip_addresses, DROP controllers, DROP timesources, CHANGE sbts_status sbts_status TINYINT(1) DEFAULT NULL, CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT NULL, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT NULL, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT NULL, CHANGE sbts_owner sbts_owner VARCHAR(255) DEFAULT NULL, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT NULL, CHANGE lte_state lte_state VARCHAR(255) DEFAULT NULL, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT NULL, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT NULL, CHANGE refresh_time refresh_time TIME DEFAULT NULL');
		$this->addSql('CREATE INDEX sbts_status ON device_entity (sbts_status)');
		$this->addSql('CREATE INDEX sbts_hw_configuration ON device_entity (sbts_hw_configuration)');
		$this->addSql('CREATE INDEX sbts_sw_release ON device_entity (sbts_sw_release)');
		$this->addSql('CREATE INDEX sbts_state ON device_entity (sbts_state)');
		$this->addSql('CREATE INDEX lte_state ON device_entity (lte_state)');
		$this->addSql('CREATE INDEX wcdma_state ON device_entity (wcdma_state)');
		$this->addSql('CREATE INDEX gsm_state ON device_entity (gsm_state)');
		$this->addSql('CREATE INDEX refresh_time ON device_entity (refresh_time)');
		$this->addSql('ALTER TABLE hardware_module_entity CHANGE product_name product_name VARCHAR(255) DEFAULT NULL, CHANGE product_code product_code VARCHAR(255) DEFAULT NULL, CHANGE serial_number serial_number VARCHAR(255) DEFAULT NULL, CHANGE usage_state usage_state VARCHAR(255) DEFAULT NULL, CHANGE type type INT DEFAULT NULL');
		$this->addSql('CREATE INDEX product_name ON hardware_module_entity (product_name)');
		$this->addSql('CREATE INDEX product_code ON hardware_module_entity (product_code)');
		$this->addSql('CREATE INDEX serial_number ON hardware_module_entity (serial_number)');
		$this->addSql('CREATE INDEX usage_state ON hardware_module_entity (usage_state)');
		$this->addSql('CREATE INDEX type ON hardware_module_entity (type)');
		$this->addSql('ALTER TABLE settings_entity CHANGE global_refresh_time global_refresh_time TIME DEFAULT NULL');
		$this->addSql('ALTER TABLE sync_source_entity CHANGE sync_input_type sync_input_type VARCHAR(255) DEFAULT NULL, CHANGE sync_input_prio sync_input_prio VARCHAR(255) DEFAULT NULL, CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE availability availability VARCHAR(255) DEFAULT NULL, CHANGE usability usability VARCHAR(255) DEFAULT NULL');
		$this->addSql('CREATE INDEX sync_input_type ON sync_source_entity (sync_input_type)');
		$this->addSql('CREATE INDEX sync_input_prio ON sync_source_entity (sync_input_prio)');
		$this->addSql('CREATE INDEX is_active ON sync_source_entity (is_active)');
		$this->addSql('CREATE INDEX availability ON sync_source_entity (availability)');
		$this->addSql('CREATE INDEX usability ON sync_source_entity (usability)');
	}

	/**
	 * @param Schema $schema
	 * @throws DBALException
	 */
	public function down(Schema $schema): void {
		// this down() migration is auto-generated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

		$this->addSql('DROP INDEX severity ON alarm_entity');
		$this->addSql('DROP INDEX observation_time ON alarm_entity');
		$this->addSql('DROP INDEX alarm_id ON alarm_entity');
		$this->addSql('DROP INDEX fault_id ON alarm_entity');
		$this->addSql('DROP INDEX alarm_name ON alarm_entity');
		$this->addSql('DROP INDEX fault_severity ON alarm_entity');
		$this->addSql('DROP INDEX alarm_detail ON alarm_entity');
		$this->addSql('DROP INDEX alarm_detail_number ON alarm_entity');
		$this->addSql('DROP INDEX fault_description ON alarm_entity');
		$this->addSql('ALTER TABLE alarm_entity CHANGE severity severity VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE observation_time observation_time DATETIME DEFAULT \'NULL\', CHANGE alarm_id alarm_id INT DEFAULT NULL, CHANGE fault_id fault_id INT DEFAULT NULL, CHANGE alarm_name alarm_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE fault_severity fault_severity VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alarm_detail alarm_detail VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE alarm_detail_number alarm_detail_number INT DEFAULT NULL, CHANGE fault_description fault_description VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
		$this->addSql('DROP INDEX sbts_status ON device_entity');
		$this->addSql('DROP INDEX sbts_hw_configuration ON device_entity');
		$this->addSql('DROP INDEX sbts_sw_release ON device_entity');
		$this->addSql('DROP INDEX sbts_state ON device_entity');
		$this->addSql('DROP INDEX lte_state ON device_entity');
		$this->addSql('DROP INDEX wcdma_state ON device_entity');
		$this->addSql('DROP INDEX gsm_state ON device_entity');
		$this->addSql('DROP INDEX refresh_time ON device_entity');
		$this->addSql('ALTER TABLE device_entity ADD active_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD passive_sw_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, ADD ip_addresses LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, ADD controllers LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, ADD timesources LONGTEXT DEFAULT NULL COLLATE utf8mb4_bin, CHANGE sbts_status sbts_status TINYINT(1) DEFAULT \'NULL\', CHANGE sbts_hw_configuration sbts_hw_configuration VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sbts_sw_release sbts_sw_release VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_information_refresh last_information_refresh DATETIME DEFAULT \'NULL\', CHANGE sbts_owner sbts_owner VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE sbts_state sbts_state TINYINT(1) DEFAULT \'NULL\', CHANGE lte_state lte_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE wcdma_state wcdma_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gsm_state gsm_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE refresh_time refresh_time TIME DEFAULT \'NULL\'');
		$this->addSql('DROP INDEX product_name ON hardware_module_entity');
		$this->addSql('DROP INDEX product_code ON hardware_module_entity');
		$this->addSql('DROP INDEX serial_number ON hardware_module_entity');
		$this->addSql('DROP INDEX usage_state ON hardware_module_entity');
		$this->addSql('DROP INDEX type ON hardware_module_entity');
		$this->addSql('ALTER TABLE hardware_module_entity CHANGE product_name product_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE product_code product_code VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE serial_number serial_number VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE usage_state usage_state VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE type type INT DEFAULT NULL');
		$this->addSql('ALTER TABLE settings_entity CHANGE global_refresh_time global_refresh_time TIME DEFAULT \'NULL\'');
		$this->addSql('DROP INDEX sync_input_type ON sync_source_entity');
		$this->addSql('DROP INDEX sync_input_prio ON sync_source_entity');
		$this->addSql('DROP INDEX is_active ON sync_source_entity');
		$this->addSql('DROP INDEX availability ON sync_source_entity');
		$this->addSql('DROP INDEX usability ON sync_source_entity');
		$this->addSql('ALTER TABLE sync_source_entity CHANGE sync_input_type sync_input_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE sync_input_prio sync_input_prio VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE is_active is_active TINYINT(1) DEFAULT \'NULL\', CHANGE availability availability VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE usability usability VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
	}
}
