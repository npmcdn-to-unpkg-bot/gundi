<?php
namespace {
    /**
     * @return \Core\Library\Gundi\Gundi;
     */
    function Gundi(){
        return $GLOBALS['gundi_instance'];
    }
}

namespace Core\Library\Gundi {

    use Illuminate\Contracts\Container\Container;

    /**
     * @property \Core\library\Router\Router Router
     * @property \Core\Library\View\Html\Extension\Block Block
     * @property \Core\Library\Setting\Setting Setting
     * @property \Core\Library\View\Html\Extension\Asset Asset
     * @property \Core\Library\Event\Dispatcher EventDispatcher
     * @method void resolving($abstract, \Closure $callback = null)
     */
    class Gundi
    {
        /**
         * Gundi Version : major.minor.maintenance
         */
        const VERSION = '1.0.1pre-alpha';
        const CODE_NAME = 'AdaptiveMeat';
        const BROWSER_AGENT = 'Gundi';
        const PRODUCT_BUILD = '1';
        const GUNDI_API = '';
        const GUNDI_PACKAGE = 'ultimate';

        public function __construct()
        {
            $GLOBALS['gundi_instance'] = $this;
        }

        /**
         * @var \Illuminate\Container\Container
         */
        private $_oDIContainer = null;

        /**
         * Get the current product version.
         *
         * @return string
         */
        public function getVersion()
        {
            return self::VERSION;
        }

        /**
         * Get the current product version ID.
         *
         * @return int
         */
        public function getId()
        {
            return self::getVersion();
        }

        /**
         * Get the products code name.
         *
         * @return string
         */
        public function getCodeName()
        {
            return self::CODE_NAME;
        }

        /**
         * Get the products build number.
         *
         * @return int
         */
        public function getBuild()
        {
            return self::PRODUCT_BUILD;
        }

        /**
         * Get the clean numerical value of the product version.
         *
         * @return int
         */
        public function getCleanVersion()
        {
            return str_replace('.', '', self::VERSION);
        }

        /**
         * Check if a feature can be used based on the package the client
         * has installed.
         *
         * Example (STRING):
         * <code>
         * if (Gundi::isPackage('1') { }
         * </code>
         *
         * Example (ARRAY):
         * <code>
         * if (Gundi::isPackage(array('1', '2')) { }
         * </code>
         *
         * @param mixed $mPackage STRING can be used to pass the package ID, or an ARRAY to pass multipl packages.
         * @return unknown
         */
        public function isPackage($mPackage)
        {
            if (self::GUNDI_PACKAGE == '[GUNDI_PACKAGE_NAME]') {
                return false;
            }

            if (!is_array($mPackage)) {
                $mPackage = array($mPackage);
            }

            return (in_array(self::GUNDI_PACKAGE, $mPackage) ? true : false);
        }

        /**
         * Provide "powered by" link.
         *
         * @param bool $bLink TRUE to include a link to TekeNet.
         * @param bool $bVersion TRUE to include the version being used.
         * @return string Powered by TekeNet string returned.
         */
        public function link($bLink = true, $bVersion = true)
        {
            return 'Powered By ' . ($bVersion ? ' Version ' . $this->getVersion() : '');
        }

        public function __call($name, $arguments)
        {
            return call_user_func_array([$this->_oDIContainer, $name], $arguments);
        }

        /**
         * @return Container
         */
        public function getDIContainer()
        {
            return $this->_oDIContainer;
        }

        /**
         * @param object $oDIContainer
         */
        public function setDIContainer(&$oDIContainer)
        {
            $this->_oDIContainer = $oDIContainer;
        }

        public function __get($sName){
            return $this->_oDIContainer->make($sName);
        }

    }
}