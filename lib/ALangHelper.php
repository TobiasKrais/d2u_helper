<?php
namespace D2U_Helper;

/**
 * Superclass for all lang helper classes of D2U addons.
 */
abstract class ALangHelper {
	/**
	 * @var string[] Array with english replacements. Key is the wildcard,
	 * value the replacement. Every ALangHelper child has this variable
	 */
	protected $replacements_english = [];

	/**
	 * Factory method.
	 */
	abstract public static function factory();
	
	/**
	 * Get database ID of wildcard. ID is the same for all languages
	 * @param string $key Wildcard key.
	 * @return int ID
	 */
	private static function getId($key) {
		$select_id_query = "SELECT id FROM ". \rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND id > 0;";
		$select_id_sql = \rex_sql::factory();
		$select_id_sql->setQuery($select_id_query);
		if($select_id_sql->getRows() > 0) {
			return $select_id_sql->getValue('id');
		}
		else {
			$select_id_query = "SELECT MAX(id) + 1 AS max_id FROM ". \rex::getTablePrefix() ."sprog_wildcard;";
			$select_id_sql->setQuery($select_id_query);
			if($select_id_sql->getValue('max_id') != NULL) {
				return $select_id_sql->getValue('max_id');
			}
		}

		// Fallback
		return 1;
	}

	/**
	 * Installs the replacement table for this addon.
	 */
	abstract public function install();

	/**
	 * Save value in sprog wildcard table
	 * @param string $key Wildcard key
	 * @param string $value Wildcard value
	 * @param int $clang_id Wildcard language ID
	 * @param bool $overwrite Overwrite value if key already exists. Default is FALSE.
	 * @return bool TRUE if successfully saved
	 */
	protected static function saveValue($key, $value, $clang_id, $overwrite = FALSE) {
		if(!\rex_addon::get('sprog')->isAvailable()) {
			return FALSE;
		}
		
		$select_pid_query = "SELECT pid FROM ". \rex::getTablePrefix() ."sprog_wildcard WHERE wildcard = '". $key ."' AND clang_id = ". $clang_id .";";
		$select_pid_sql = \rex_sql::factory();
		$select_pid_sql->setQuery($select_pid_query);
		if($select_pid_sql->getRows() > 0) {
			if($overwrite) {
				// Update
				$query = "UPDATE ". \rex::getTablePrefix() ."sprog_wildcard SET "
					."`replace` = '". addslashes($value) ."', "
					."updatedate = '". \rex_sql::datetime() ."', "
					."updateuser = '". \rex::getUser()->getValue('login') ."' "
					."WHERE pid = ". $select_pid_sql->getValue('pid') .";COMMIT;";
				$sql = \rex_sql::factory();
				$sql->setQuery($query);
				return !$sql->hasError();
			}
		}
		else {
			// Save
			$query = "INSERT INTO ". \rex::getTablePrefix() ."sprog_wildcard SET "
				."id = ". self::getId($key) .", "
				."clang_id = ". $clang_id .", "
				."wildcard = '". $key ."', "
				."`replace` = '". addslashes($value) ."', "
				."createdate = '". \rex_sql::datetime() ."', "
				."createuser = '". \rex::getUser()->getValue('login') ."', "
				."updatedate = '". \rex_sql::datetime() ."', "
				."updateuser = '". \rex::getUser()->getValue('login') ."';";
			$sql = \rex_sql::factory();
			$sql->setQuery($query);
			return !$sql->hasError();
		}
	}
	
	/**
	 * Uninstalls the replacement table for this addon.
	 * @param int $clang_id Redaxo language ID, if 0, replacements of all languages
	 * will be deleted. Otherwise only one specified language will be deleted.
	 */
	public function uninstall($clang_id = 0) {
		foreach(array_keys($this->replacements_english) as $key) {
			if(\rex_addon::get('sprog')->isAvailable()) {
				// Delete 
				$query = "DELETE FROM ". \rex::getTablePrefix() ."sprog_wildcard "
					."WHERE wildcard = '". $key ."'". ($clang_id > 0 ? " AND clang_id = ". $clang_id : "") .";";
				$select = \rex_sql::factory();
				$select->setQuery($query);
			}
		}
	}
}
