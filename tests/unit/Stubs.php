<?php
namespace Tests\unit\Stubs;


use Core\Contract\Request\IRequest;
use Core\Library\Error\Error;
use Core\Library\Gundi\Gundi;
use Core\Library\Theme\Theme;
use Core\Library\View\JsonView;

class StubRequest extends \ArrayObject implements IRequest
{

    public function get($sName, $sType = null)
    {
        // TODO: Implement get() method.
    }

    public function post($sName, $sType = null)
    {
        // TODO: Implement post() method.
    }

    public function getExt()
    {
        // TODO: Implement getExt() method.
    }

    public function getUri()
    {
        // TODO: Implement getUri() method.
    }

    public function getHttpMethod()
    {
        // TODO: Implement getHttpMethod() method.
    }

    public function isPost()
    {
        // TODO: Implement isPost() method.
    }
}

class StubTheme extends Theme
{
    public function __construct()
    {
    }
}

class MockGundi extends Gundi
{
    public $version = '1.0.0';
    public $aService = [];

    public function make($abstract, array $parameters = [])
    {
        if (isset($this->aService[$abstract])) {
            return $this->aService[$abstract];
        }

        return parent::make($abstract, $parameters);
    }

    public function getVersion()
    {
        return $this->version;
    }
}


class StubError extends Error
{
    static public $oView = null;

    public static function display($sMsg, $iErrCode = null, $sFormat = 'html', $aData = [])
    {
        self::getView()->assign($aData);
        echo $sMsg;
    }

    private static function getView()
    {
        if (is_null(self::$oView)){
            self::$oView = new JsonView();
        }
        return self::$oView;
    }

}