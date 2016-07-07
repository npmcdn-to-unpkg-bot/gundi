<?php

namespace Core\Library\Module;


class Module
{
    static private $_sDirModule;
    /**
     * Initial modules.
     *
     * @param  integer $iId
     * @return $instance
     */
    public static function loadCoreModules()
    {
        self::$_sDirModule = Gundi()->Setting->getParam('core.dir_module');
        self::loadModules(Gundi()->Setting->getParam('core.modules'));
    }

    public static function loadModules($aModules)
    {
        foreach ($aModules as &$sModuleName) {
            self::loadModule($sModuleName);
        }
    }

    public static function loadModule($sModuleName)
    {
        $sBootsFile = self::$_sDirModule . $sModuleName . GUNDI_DS . 'Bootstrap.php';
        if (file_exists($sBootsFile)) {
            include_once $sBootsFile;
        }
    }
}