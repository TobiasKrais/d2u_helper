<?php

namespace TobiasKrais\D2UHelper;

use rex;
use rex_addon;
use rex_logger;
use rex_sql;
use rex_sql_table;
use rex_user;
use rex_version;
use Sprog\Enum\SourceType;
use Sprog\Enum\Status;
use Sprog\Model\Translation;
use Sprog\Model\Unit;
use Sprog\Repository\TranslationRepository;
use Sprog\Repository\UnitRepository;
use Sprog\Service\TranslationService;
use Sprog\Support\ContentHash;
use Throwable;

/**
 * @api
 * Superclass for all lang helper classes of D2U addons.
 */
abstract class ALangHelper
{
    /**
     * @var array<string,string> Array with english replacements. Key is the wildcard,
     * value the replacement. Every ALangHelper child has this variable
     */
    public $replacements_english = [];

    /**
     * Factory method.
     * @return ALangHelper Lang helper object
     */
    abstract public static function factory();

    /**
     * Get database ID of wildcard. ID is the same for all languages.
     * @param string $key wildcard key
     * @return int ID
     */
    private static function getId($key)
    {
        $select_id_query = 'SELECT id FROM '. rex::getTablePrefix() .'sprog_wildcard WHERE wildcard = :key AND id > 0;';
        $select_id_sql = rex_sql::factory();
        $select_id_sql->setQuery($select_id_query, ['key' => $key]);
        if ($select_id_sql->getRows() > 0) {
            return (int) $select_id_sql->getValue('id');
        }

        $select_id_query = 'SELECT MAX(id) + 1 AS max_id FROM '. rex::getTablePrefix() .'sprog_wildcard;';
        $select_id_sql->setQuery($select_id_query);
        if ((int) $select_id_sql->getValue('max_id') > 0) {
            return (int) $select_id_sql->getValue('max_id');
        }

        // Fallback
        return 1;
    }

    /**
     * Installs the replacement table for this addon.
     */
    abstract public function install(): void;

    /**
     * Checks whether the Sprog v1 wildcard table exists.
     * @return bool true if the sprog_wildcard table exists
     */
    protected static function sprogWildcardTableExists(): bool
    {
        return rex_sql_table::get(rex::getTablePrefix() . 'sprog_wildcard')->exists();
    }

    /**
     * Checks whether Sprog v2 (>= 2.0) with its new unit/translation schema and
     * repository API is available. Detection is done via the REDAXO addon
     * availability plus a version check (not by probing internal classes).
     * @return bool true if Sprog v2 is available
     */
    protected static function sprogV2Available(): bool
    {
        $sprog = rex_addon::get('sprog');
        // Sprog 2.x (incl. 2.0.0-beta*) introduced the unit/translation schema
        // and repository API; 1.x uses the flat sprog_wildcard table.
        return $sprog->isAvailable()
            && rex_version::compare((string) $sprog->getVersion(), '2.0.0-beta1', '>=');
    }

    /**
     * Save value as a Sprog wildcard.
     *
     * Sprog v2 (>= 2.0) uses a new schema (sprog_unit/sprog_translation); the
     * value is written through its repository API. Sprog v1 uses the flat
     * sprog_wildcard table. If neither the v2 API nor the v1 table is present,
     * nothing is written (no tables are created).
     * @param string $key Wildcard key
     * @param string $value Wildcard value
     * @param int $clang_id Wildcard language ID
     * @param bool $overwrite Overwrite value if key already exists. Default is false.
     * @return bool true if successfully saved
     */
    protected static function saveValue($key, $value, $clang_id, $overwrite = false)
    {
        if (!rex_addon::get('sprog')->isAvailable()) {
            return false;
        }

        $clang_id = (int) $clang_id;

        if (self::sprogV2Available()) {
            return self::saveValueV2((string) $key, (string) $value, $clang_id, (bool) $overwrite);
        }

        if (self::sprogWildcardTableExists()) {
            return self::saveValueV1((string) $key, (string) $value, $clang_id, (bool) $overwrite);
        }

        // Neither Sprog v2 API nor the v1 wildcard table is present: do not
        // create any table and do not insert anything.
        return false;
    }

    /**
     * Save value in the Sprog v2 unit/translation schema via its repository API.
     * @param string $key Wildcard key
     * @param string $value Wildcard value
     * @param int $clang_id Redaxo language ID
     * @param bool $overwrite Overwrite an existing translation value
     * @return bool true if successfully saved
     */
    private static function saveValueV2(string $key, string $value, int $clang_id, bool $overwrite): bool
    {
        try {
            $units = new UnitRepository();
            $unit = $units->findByKey('wildcard', $key);
            if (null === $unit) {
                $unit = $units->save(new Unit(
                    id: null,
                    namespace: 'wildcard',
                    unitKey: $key,
                    sourceType: SourceType::Wildcard,
                ));
            }
            $unit_id = (int) $unit->id;

            $translations = new TranslationRepository();
            $existing = $translations->findForUnitAndClang($unit_id, $clang_id);
            if (null === $existing || $overwrite) {
                // v1 wildcards had no status and were always live -> approved.
                $status = '' === $value ? Status::Missing : Status::Approved;
                $translations->save(new Translation(
                    id: null !== $existing ? $existing->id : null,
                    unitId: $unit_id,
                    clangId: $clang_id,
                    value: $value,
                    valueHash: '' === $value ? null : ContentHash::of($value),
                    sourceHashAtTranslation: null,
                    status: $status,
                ));
            }

            // Make sure every clang has a (missing) row so the Sprog inbox stays consistent.
            TranslationService::create()->ensureRowsForUnit($unit);

            return true;
        } catch (Throwable $e) {
            rex_logger::logException($e);
            return false;
        }
    }

    /**
     * Save value in the legacy Sprog v1 sprog_wildcard table.
     * @param string $key Wildcard key
     * @param string $value Wildcard value
     * @param int $clang_id Redaxo language ID
     * @param bool $overwrite Overwrite value if key already exists
     * @return bool true if successfully saved
     */
    private static function saveValueV1(string $key, string $value, int $clang_id, bool $overwrite): bool
    {
        $login = rex::getUser() instanceof rex_user ? (string) rex::getUser()->getValue('login') : '';

        $select_pid_query = 'SELECT pid FROM '. rex::getTablePrefix() .'sprog_wildcard WHERE wildcard = :key AND clang_id = :clang_id;';
        $select_pid_sql = rex_sql::factory();
        $select_pid_sql->setQuery($select_pid_query, ['key' => $key, 'clang_id' => $clang_id]);
        if ($select_pid_sql->getRows() > 0) {
            if ($overwrite) {
                // Update
                $query = 'UPDATE '. rex::getTablePrefix() .'sprog_wildcard SET '
                    .'`replace` = :value, '
                    .'updatedate = :updatedate, '
                    .'updateuser = :updateuser '
                    .'WHERE pid = :pid;';
                $sql = rex_sql::factory();
                $sql->setQuery($query, [
                    'value' => $value,
                    'updatedate' => rex_sql::datetime(),
                    'updateuser' => $login,
                    'pid' => (int) $select_pid_sql->getValue('pid'),
                ]);
                return !$sql->hasError();
            }
        } else {
            // Save
            $query = 'INSERT INTO '. rex::getTablePrefix() .'sprog_wildcard SET '
                .'id = :id, '
                .'clang_id = :clang_id, '
                .'wildcard = :key, '
                .'`replace` = :value, '
                .'createdate = :createdate, '
                .'createuser = :createuser, '
                .'updatedate = :updatedate, '
                .'updateuser = :updateuser;';
            $sql = rex_sql::factory();
            $sql->setQuery($query, [
                'id' => self::getId($key),
                'clang_id' => $clang_id,
                'key' => $key,
                'value' => $value,
                'createdate' => rex_sql::datetime(),
                'createuser' => $login,
                'updatedate' => rex_sql::datetime(),
                'updateuser' => $login,
            ]);
            return !$sql->hasError();
        }

        return true;
    }

    /**
     * Uninstalls the replacement table for this addon.
     * @param int $clang_id Redaxo language ID, if 0, replacements of all languages
     * will be deleted. Otherwise only one specified language will be deleted.
     */
    public function uninstall($clang_id = 0): void
    {
        $clang_id = (int) $clang_id;
        if (!rex_addon::get('sprog')->isAvailable()) {
            return;
        }

        $v2 = self::sprogV2Available();
        $v1 = !$v2 && self::sprogWildcardTableExists();
        if (!$v2 && !$v1) {
            return;
        }

        foreach (array_keys($this->replacements_english) as $key) {
            if ($v2) {
                try {
                    $units = new UnitRepository();
                    $unit = $units->findByKey('wildcard', (string) $key);
                    if (null === $unit || null === $unit->id) {
                        continue;
                    }
                    $translations = new TranslationRepository();
                    if ($clang_id > 0) {
                        // Only remove the translation of the given language.
                        $translation = $translations->findForUnitAndClang((int) $unit->id, $clang_id);
                        if (null !== $translation && null !== $translation->id) {
                            $translations->delete((int) $translation->id);
                        }
                    } else {
                        // Remove the whole unit including all its translations.
                        $translations->deleteByUnit((int) $unit->id);
                        $units->delete((int) $unit->id);
                    }
                } catch (Throwable $e) {
                    rex_logger::logException($e);
                }
                continue;
            }

            // Sprog v1
            $query = 'DELETE FROM '. rex::getTablePrefix() .'sprog_wildcard '
                .'WHERE wildcard = :key'. ($clang_id > 0 ? ' AND clang_id = :clang_id' : '') .';';
            $params = ['key' => $key];
            if ($clang_id > 0) {
                $params['clang_id'] = $clang_id;
            }
            $select = rex_sql::factory();
            $select->setQuery($query, $params);
        }
    }
}
