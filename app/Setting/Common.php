<?php
//database configuration
$_CONF['db.driver'] = 'mysql';
$_CONF['db.host'] = 'localhost';
$_CONF['db.user'] = 'root';
$_CONF['db.pass'] = 'qaz';
$_CONF['db.name'] = 'test';
$_CONF['db.port'] = '';

//framework conf
$_CONF['core.http'] = 'http://';
$_CONF['core.https'] = 'https://';
$_CONF['core.protocol'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? $_CONF['core.https'] : $_CONF['core.http']);

$_CONF['core.host'] = 'localhost';
$_CONF['core.folder'] = '/gundi/';


$_CONF['core.dir_module'] = GUNDI_DIR_MODULE;
$_CONF['core.tmp_ext'] = GUNDI_TMP_EXT;
$_CONF['core.themes_dir'] = GUNDI_THEMES_DIR;
$_CONF['core.app_dir'] = GUNDI_APP_DIR;

$_CONF['core.path'] = $_CONF['core.protocol'] . $_CONF['core.host'] . $_CONF['core.folder'];

$_CONF['core.prefix'] = 'GUNDI_';
$_CONF['core.session_prefix'] = 'GUNDI_';
$_CONF['core.default_session_container'] = 'GUNDI';
$_CONF['core.servers'] = [];
$_CONF['core.modules'] = ['Core', 'Error', 'Catalog'];