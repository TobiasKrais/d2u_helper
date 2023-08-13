<?php

namespace D2U_Helper;

use rex;
use rex_addon;
use rex_config;
use rex_sql;
use rex_user;

/**
 * @api
 * Administrates CronJob for D2U Helper Addons.
 */
abstract class ACronJob
{
    /** @var string Name of CronJob */
    protected $name = '';

    /**
     * Activate CronJob.
     * @return bool true if successful, otherwise false
     */
    public function activate()
    {
        if (rex_addon::get('cronjob')->isAvailable() && self::isInstalled()) {
            $query = 'UPDATE `'. rex::getTablePrefix() .'cronjob` SET '
                .'`status` = 1, '
                ."nexttime = '". date('Y-m-d H:i:s', strtotime('+1 min')) ."' "
                ."WHERE `name` = '". $this->name ."'";
            $sql = rex_sql::factory();
            $sql->setQuery($query);

            self::setConfig();

            return true;
        }

        return false;

    }

    /**
     * Deactivate CronJob.
     * @return bool true if successful, otherwise false
     */
    public function deactivate()
    {
        if (rex_addon::get('cronjob')->isAvailable() && self::isInstalled()) {
            $query = 'UPDATE `'. rex::getTablePrefix() ."cronjob` SET status = 0 WHERE `name` = '". $this->name ."'";
            $sql = rex_sql::factory();
            $sql->setQuery($query);
            return true;
        }

        return false;

    }

    /**
     * Delete CronJob.
     * @return bool true if successful, otherwise false
     */
    public function delete()
    {
        if (rex_addon::get('cronjob')->isAvailable()) {
            $query = 'DELETE FROM `'. rex::getTablePrefix() ."cronjob` WHERE `name` = '". $this->name ."'";
            $sql = rex_sql::factory();
            $sql->setQuery($query);
            return !$sql->hasError();
        }
        return false;
    }

    /**
     * Create a new instance of object.
     * @return ACronJob CronJob object
     */
    abstract public static function factory();

    /**
     * Install CronJob.
     */
    abstract public function install(): void;

    /**
     * Checks if  cron job is installed.
     * @return bool true if CronJob is installed, otherwise false
     */
    public function isInstalled()
    {
        if (rex_addon::get('cronjob')->isAvailable()) {
            $query = 'SELECT `name` FROM `'. rex::getTablePrefix() ."cronjob` WHERE `name` = '". $this->name ."'";
            $sql = rex_sql::factory();
            $sql->setQuery($query);
            if ($sql->getRows() > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Install CronJob. Its also activated. This is an internal parent method for
     * doing the install job.
     * @param string $description Description
     * @param string $php_code PHP Code, please just one line
     * @param string $interval JSON encoded interval, e.g. {\"minutes\":[0],\"hours\":[0],\"days\":\"all\",\"weekdays\":[1],\"months\":\"all\"}
     * @param bool $activate true if CronJob should be activated, otherwise false
     */
    protected function save($description, $php_code, $interval, $activate = true): void
    {
        if (rex_addon::get('cronjob')->isAvailable()) {
            $query = 'INSERT INTO `'. rex::getTablePrefix() .'cronjob` (`name`, `description`, `type`, `parameters`, `interval`, `nexttime`, `environment`, `execution_moment`, `execution_start`, `status`, `createdate`, `createuser`) VALUES '
                ."('". $this->name ."', '". $description ."', 'rex_cronjob_phpcode', '{\"rex_cronjob_phpcode_code\":\"". $php_code ."\"}', '". $interval ."', '". date('Y-m-d H:i:s', strtotime('+5 min')) ."', '|frontend|backend|', 0, '1970-01-01 01:00:00', ". ($activate ? '1' : '0') .", CURRENT_TIMESTAMP, '". (rex::getUser() instanceof rex_user ? rex::getUser()->getLogin() : '') ."');";
            $sql = rex_sql::factory();
            $sql->setQuery($query);

            self::setConfig();
        }
    }

    /**
     * Set nexttime rex_config for CronJob addon.
     */
    private function setConfig(): void
    {
        if (rex_config::get('cronjob', 'nexttime', 0) > strtotime('+5 minutes')) {
            rex_config::set('cronjob', 'nexttime', strtotime('+5 minutes'));
        }
    }
}
