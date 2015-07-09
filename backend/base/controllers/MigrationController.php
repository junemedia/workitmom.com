<?php

/**
 *	Migration
 */
class MigrationController extends BackendController
{
	/**
	 *	Migrate config settings (to be serialized)
	 *
	 *	@access public
	 */
	public function migrateSettings()
	{
		$migrationModel = BluApplication::getModel('migration');
		if ($migrationModel->migrateSettings()) {
			echo 'Settings migrated';
		}
	}
}

?>