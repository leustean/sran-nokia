<?php


namespace App\Repository;


use App\Entity\AlarmEntity;
use App\Entity\DeviceEntity;
use App\Entity\HardwareModuleEntity;
use App\Entity\SyncSourceEntity;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class SearchFieldsRepository {

	protected static $tables = [
		'device' => [
			'tableDisplayName' => 'Device',
			'tableName' => DeviceEntity::class,
			'realTableName' => 'device_entity',
			'tableCondition' => null,
			'tableColumns' => [
				'sbts_sw_release' => [
					'displayName' => 'SW release',
					'columnName' => 'sbtsSwRelease'
				],
				'sbts_state' => [
					'displayName' => 'State',
					'columnName' => 'sbtsState'
				],
				'lte_state' => [
					'displayName' => 'LTE state',
					'columnName' => 'lteState'
				],
				'wcdma_state' => [
					'displayName' => 'WCDMA state',
					'columnName' => 'wcdmaState'
				],
				'gsm_state' => [
					'displayName' => 'GSM state',
					'columnName' => 'gsmState'
				]
			]
		],
		'smod' => [
			'tableDisplayName' => 'System module',
			'tableName' => HardwareModuleEntity::class,
			'realTableName' => 'hardware_module_entity',
			'tableCondition' => 'type=1',
			'tableColumns' => [
				'product_name' => [
					'displayName' => 'Product Name',
					'columnName' => 'productName'
				],
				'product_code' => [
					'displayName' => 'Product Code',
					'columnName' => 'productCode'
				],
				'serial_number' => [
					'displayName' => 'Serial Number',
					'columnName' => 'serialNumber'
				],
				'usage_state' => [
					'displayName' => 'Usage state',
					'columnName' => 'usageState'
				]
			]
		],
		'bmod' => [
			'tableDisplayName' => 'Baseband module',
			'tableName' => HardwareModuleEntity::class,
			'realTableName' => 'hardware_module_entity',
			'tableCondition' => 'type=2',
			'tableColumns' => [
				'product_name' => [
					'displayName' => 'Product Name',
					'columnName' => 'productName'
				],
				'product_code' => [
					'displayName' => 'Product Code',
					'columnName' => 'productCode'
				],
				'serial_number' => [
					'displayName' => 'Serial Number',
					'columnName' => 'serialNumber'
				],
				'usage_state' => [
					'displayName' => 'Usage state',
					'columnName' => 'usageState'
				]
			]
		],
		'rfmod' => [
			'tableDisplayName' => 'Radio module',
			'tableName' => HardwareModuleEntity::class,
			'realTableName' => 'hardware_module_entity',
			'tableCondition' => 'type=3',
			'tableColumns' => [
				'product_name' => [
					'displayName' => 'Product Name',
					'columnName' => 'productName'
				],
				'product_code' => [
					'displayName' => 'Product Code',
					'columnName' => 'productCode'
				],
				'serial_number' => [
					'displayName' => 'Serial Number',
					'columnName' => 'serialNumber'
				],
				'usage_state' => [
					'displayName' => 'Usage state',
					'columnName' => 'usageState'
				]
			]
		],
		'alarm' => [
			'tableDisplayName' => 'Alarm',
			'tableName' => AlarmEntity::class,
			'realTableName' => 'alarm_entity',
			'tableCondition' => null,
			'tableColumns' => [
				'severity' => [
					'displayName' => 'Severity',
					'columnName' => 'severity'
				],
				'alarm_id' => [
					'displayName' => 'Alarm ID',
					'columnName' => 'alarmId'
				],
				'fault_id' => [
					'displayName' => 'Fault ID',
					'columnName' => 'faultId'
				],
				'alarm_name' => [
					'displayName' => 'Alarm name',
					'columnName' => 'alarmName'
				],
				'fault_severity' => [
					'displayName' => 'Fault severity',
					'columnName' => 'faultSeverity'
				],
				'alarm_detail' => [
					'displayName' => 'Alarm detail',
					'columnName' => 'alarmDetail'
				],
				'alarm_detail_number' => [
					'displayName' => 'Alarm detail number',
					'columnName' => 'alarmDetailNumber'
				],
				'fault_description' => [
					'displayName' => 'Fault description',
					'columnName' => 'faultDescription'
				]
			]
		],
		'syncSource' => [
			'tableDisplayName' => 'Sync source',
			'tableName' => SyncSourceEntity::class,
			'realTableName' => 'sync_source_entity',
			'tableCondition' => null,
			'tableColumns' => [
				'sync_input_type' => [
					'displayName' => 'Sync input type',
					'columnName' => 'syncInputType'
				],
				'sync_input_prio' => [
					'displayName' => 'Sync input PRIO',
					'columnName' => 'syncInputPrio'
				],
				'is_active' => [
					'displayName' => 'Active',
					'columnName' => 'isActive'
				],
				'availability' => [
					'displayName' => 'Availability',
					'columnName' => 'availability'
				],
				'usability' => [
					'displayName' => 'Usability',
					'columnName' => 'usability'
				]
			]
		]
	];

	protected static $fetched = false;

	/**
	 * @var Connection
	 */
	protected $con;

	public function __construct(Connection $con) {
		$this->con = $con;
	}

	/**
	 * @param      $table
	 * @param      $field
	 * @param null $condition
	 * @return array
	 * @throws DBALException
	 */
	protected function findAllDistinctValues($table, $field, $condition = null): array {
		$query = 'SELECT DISTINCT(`' . $field . '`) `value` FROM `' . $table . '` WHERE `' . $field . '` IS NOT NULL';
		if ($condition !== null) {
			$query .= ' AND ' . $condition;
		}

		$values = [];
		foreach ($this->con->executeQuery($query) as $row) {
			$values[] = $row['value'];
		}

		return $values;
	}

	/**
	 * @return array
	 * @throws DBALException
	 */
	public function getTables(): array {
		if (self::$fetched) {
			return self::$tables;
		}
		foreach (self::$tables as $tableName => $table) {
			foreach (array_keys($table['tableColumns']) as $fieldName) {
				self::$tables[$tableName]['tableColumns'][$fieldName]['data'] = $this->findAllDistinctValues($table['realTableName'], $fieldName, $table['tableCondition']);
			}
		}
		self::$fetched = true;
		return self::$tables;
	}

}