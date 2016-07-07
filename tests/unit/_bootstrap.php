<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Jenssegers\Mongodb\Connection;

defined('GUNDI_DS') or define('GUNDI_DS', DIRECTORY_SEPARATOR);
defined('GUNDI_ROOT') or define('GUNDI_ROOT', __DIR__ . '/../..' . GUNDI_DS);
defined('GUNDI_APP_DIR') or define('GUNDI_APP_DIR', GUNDI_ROOT . 'app' . GUNDI_DS);
defined('GUNDI_DIR_MODULE') or define('GUNDI_DIR_MODULE', GUNDI_APP_DIR . 'Module' . GUNDI_DS);
defined('GUNDI_TMP_EXT') or define('GUNDI_TMP_EXT', '.php');
defined('GUNDI_DIR_SETTING') or define('GUNDI_DIR_SETTING', GUNDI_APP_DIR . 'Setting'. GUNDI_DS);
defined('GUNDI_THEMES_DIR') or define('GUNDI_THEMES_DIR', GUNDI_APP_DIR . 'Template'. GUNDI_DS);

$oSetting = new \Core\Library\Setting\Setting();

/**
 * connect to db
 */

$oCapsule = new Capsule();

$oCapsule->addConnection([
    'driver' => $oSetting->getParam('db.driver'),
    'host' => $oSetting->getParam('db.host'),
    'database' => $oSetting->getParam('db.name'),
    'username' => $oSetting->getParam('db.user'),
    'password' => $oSetting->getParam('db.pass'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
]);

$oCapsule->setAsGlobal();
$oCapsule->bootEloquent();
