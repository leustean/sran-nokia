<?php

namespace App\Repository;

use App\Entity\DeployOption;

class DeployOptionRepository {

	private static $options = [
		['id' => 0, 'name' => 'test', 'command' =>'ls'],
		['id' => 1, 'name' => 'git-pull', 'command' =>'git pull 2>&1'],
		['id' => 2, 'name' => 'npm-install', 'command' =>'npm install 2>&1'],
		['id' => 3, 'name' => 'compile-scss', 'command' =>'npm run compile-scss 2>&1'],
		['id' => 4, 'name' => 'composer-install', 'command' =>'composer install 2>&1'],
		['id' => 5, 'name' => 'composer-update', 'command' =>'composer update 2>&1'],
		['id' => 6, 'name' => 'composer-update', 'command' =>'composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader 2>&1'],
		['id' => 7, 'name' => 'make-empty-migration-class', 'command' =>'php bin/console doctrine:migrations:generate --no-interaction'],
		['id' => 8, 'name' => 'perform-db-migration', 'command' =>'php bin/console doctrine:migrations:diff --no-interaction'],
		['id' => 9, 'name' => 'make-db-migration-file', 'command' =>'php bin/console make:migration --no-interaction'],
		['id' => 10, 'name' => 'perform-db-migration', 'command' =>'php bin/console doctrine:migrations:migrate --no-interaction'],
	];

	private $deployOptions;

	public function __construct() {
		foreach (self::$options as $option){
			$this->deployOptions[$option['id']] = new DeployOption($option['id'],$option['name'],$option['command']);
		}
	}

	/**
	 * @return DeployOption[]
	 */
	public function findAll(): array {
		return $this->deployOptions;
	}

	/**
	 * @param $id
	 * @return DeployOption|null
	 */
	public function find($id): ?DeployOption {
		return $this->deployOptions[$id] ?? null;
	}

}
