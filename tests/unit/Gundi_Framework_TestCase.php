<?php
use Core\Library\Gundi\Gundi;

class Gundi_Framework_TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $oGundi
     */
    protected $oGundi;

    protected $aService = [];

    public function setUp()
    {
        $this->oGundi = $this->getMock(Gundi::class, [], [], '', false);
        $GLOBALS['gundi_instance'] = $this->oGundi;
    }

    /**
     * @param string $sClassName
     * @param string $sServiceName
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockForService
    (
        $sClassName,
        $sServiceName = null,
        $aMethods = [],
        $aArguments = [],
        $bCallOriginalConstructor = true,
        $bCallOriginalClone = true
    )
    {
        if (is_null($sServiceName)) {
            $sClass = new ReflectionClass($sClassName);
            $sServiceName = $sClass->getShortName();
        }
        $mock = $this->getMock($sClassName, $aMethods, $aArguments, '', $bCallOriginalConstructor, $bCallOriginalClone);
        if (empty($this->aService)) {
            $this->defineExpectationForServices();
        }
        $this->addService($sServiceName, $mock);
        return $mock;
    }

    protected function addService($sName, $oService)
    {
        $this->aService[$sName] = $oService;
    }

    public function getService($serviceName)
    {
        return $this->aService[$serviceName];
    }

    protected  function assertMethodExist($mClass, $sMethod)
    {
        $sClass = is_string($mClass) ? $mClass : get_class($mClass);
        $oReflectionClass = new ReflectionClass($sClass);
        $this->assertTrue($oReflectionClass->hasMethod($sMethod), "\"$sMethod\" method not exist in class \"$sClass\"");
    }

    private function defineExpectationForServices()
    {
        $this->oGundi->expects($this->any())
            ->method('__get')
            ->will($this->returnCallback([$this, 'getService']));
    }

}