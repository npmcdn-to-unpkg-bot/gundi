<?php

use Core\Library\Module\Module;

/**
 * Set error reporting enviroment
 */
error_reporting((GUNDI_DEBUG ? E_ALL | E_STRICT : 0));

/**
 * Turn on custom error handling.
 */

set_error_handler('Core\Library\Error\Error::errorHandler');

/**
 * Set time zone of server
 */
date_default_timezone_set(Gundi()->Setting->getParam('core.default_time_zone_offset'));

/**
 * Start sessions.
 */
Gundi()->Session->start();

/**
 * check spoofing session
 */
function generateSessionSecKey()
{
    return md5(Gundi()->Setting->getParam('core.session_prefix') . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

$sSessionSecurityKey = Gundi()->Session->get('secKey');
$sRightSessionSecurityKey = generateSessionSecKey();
if (!empty($sSessionSecurityKey) && $sSessionSecurityKey != $sRightSessionSecurityKey) {
    die('ACCESS DENY!');
} else {
    Gundi()->Session->set('secKey', $sRightSessionSecurityKey);
}

/**
 * check token if is post
 */
if (Gundi()->Request->isPost()) {
    if (!Gundi()->Token->isValid()) {
        die('The tokens do not match');
    }
}

/**
 * collect routes
 */

require_once(GUNDI_ROOT . 'app' . GUNDI_DS . 'Setting' . GUNDI_DS . 'Routes.php');

Module::loadCoreModules();


/**
 * run handler
 */
Gundi()->Dispatch->dispatch();

