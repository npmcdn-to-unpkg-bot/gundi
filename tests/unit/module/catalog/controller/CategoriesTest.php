<?php
namespace tests\unit\module\catalog\controller;

use Core\Library\Request\Request;
use Module\Catalog\Component\Controller\Categories as CategoriesController;
use Module\Catalog\Model\Categories as ModelCategories;
use Core\Library\Error\Error;

class CategoriesTest extends \Gundi_Framework_TestCase
{
    /**
     * @var Request
     */
    private $oRequest;

    /**
     * @var ModelCategories
     */
    private $oModel;

    /**
     * @var CategoriesController
     */
    private $oController;

    /**
     * @var \Core\Library\View\JsonView
     */
    private $oView;

    private $aCategory = [];

    public function setUp()
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = '';
        $oRequest = $this->getMockForService(MockRequest::class, 'Request', ['get'], [], false);
        $oRequest->method('get')->will($this->returnValue(['name'=>'test2']));

        $this->oRequest = new MockRequest();
        $this->oModel = new ModelCategories();
        $this->oModel->truncate();
        $this->aCategory[] = $this->oModel->create([
            'name' => 'Processors',
            'category_parent_id' => null
        ]);
        $this->aCategory[] = $this->oModel->create([
            'name' => 'HDD',
            'category_parent_id' => null
        ]);

        $this->oController = new CategoriesController(
            $this->oModel,
            $this->oRequest,
            new stubCategoriesError(),
            new \Core\Library\Validator\Validator()
        );
        $this->oView = new \Core\Library\View\JsonView();
        $this->oController->setView($this->oView);

    }


    public function testIndex()
    {
        $this->oRequest->set(['page'=>'1']);
        $this->oController->index();

        $oView = $this->oController->getView();
        $aVar = $oView->getVar('categories')->toArray();
        $this->assertEquals('Processors', $aVar['data']['0']['name']);
    }

    public function testAdd()
    {
        $this->oRequest->set('id', $this->aCategory[0]->getKey());
        $this->oController->add();
        $oView = $this->oController->getView();
        $aCategories = $oView->getVar('categories')->toArray();
        $aCategory = $oView->getVar('category');
        $this->assertEquals('HDD', $aCategories['1']['name']);
        $this->assertEquals('Processors', $aCategory['name']);
    }

    public function testCreate()
    {
        $this->oRequest->set('category',
            [
                'category_parent_id'=>$this->aCategory[0]->getKey(),
                'name' => 'test'
            ]
        );

        $this->oController->create();
        $oView = $this->oController->getView();
        $aResponse = $oView->getVar('response');
        $this->assertEquals('Category successfully added', $aResponse['message']);

        $this->oRequest->set('category',
            [
                'category_parent_id'=>null,
                'name' => 'test2'
            ]
        );
        $this->oController->create();
        $oView = $this->oController->getView();
        $aResponse = $oView->getVar('response');
        $this->assertEquals('Category successfully added', $aResponse['message']);

        $this->oRequest->set('category', []);
        $this->oController->create();
        $oView = $this->oController->getView();
        $oView->getVar('response');
        $this->expectOutputString('Provide all fields');
    }

    public function testDelete()
    {
        $this->oController->delete(null);
        $this->expectOutputString('Unable delete category from database');
        ob_clean();
        $this->oController->delete($this->aCategory[0]->getKey());
        $this->expectOutputString('');
    }

    public function testEdit()
    {
        $this->oController->edit($this->aCategory[1]->getKey());
        $oView = $this->oController->getView();
        $aCategory = $oView->getVar('category');
        $this->assertEquals('HDD', $aCategory['name']);
    }

    public function testUpdate()
    {
        $this->oController->update($this->aCategory[1]->getKey());
        $this->expectOutputString('');
        $oRequest = $this->getMockForService(MockRequest::class, 'Request', ['get'], [], false);
        $oRequest->method('get')->will($this->returnValue([]));
        $this->oController->update(null);
        $this->expectOutputString('Provide all fields');
    }

    public function testShow()
    {
        $this->oController->show($this->aCategory[1]->getKey());
        $oView = $this->oController->getView();
        $aCategory = $oView->getVar('category')->toArray();
        $this->assertEquals('HDD', $aCategory['name']);
    }


}

class stubCategoriesError extends Error
{

    public static function display($sMsg, $iErrCode = null, $sFormat = 'html')
    {
        echo $sMsg;
    }
}

class MockRequest extends Request{
    private $_aArgs = [];

    public function get($sVar, $mDef='')
    {
        return isset($this->_aArgs[$sVar])?$this->_aArgs[$sVar]:'';
    }

    public function set($mName, $sValue = null)
    {
        if (!is_array($mName) && $sValue !== null) {
            $mName = array($mName => $sValue);
        }

        foreach ($mName as $sKey => $sValue) {
            $this->_aArgs[$sKey] = $sValue;
        }
    }
    public function getRequests()
    {
        return $this->_aArgs;
    }
}
