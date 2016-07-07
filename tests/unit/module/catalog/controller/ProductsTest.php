<?php
namespace tests\unit\module\catalog\controller;

use Core\Library\Request\Request;
use Core\Library\Validator\Validator;
use Module\Catalog\Component\Controller\Products as ProductsController;
use Module\Catalog\Model\Categories as ModelCategories;
use Module\Catalog\Model\Products as ModelProducts;
use Core\Library\Error\Error;

class ProductsTest extends \Gundi_Framework_TestCase
{
    /**
     * @var Request
     */
    private $oRequest;

    /**
     * @var ModelCategories
     */
    private $oModelCategories;

    /**
     * @var ModelCategories
     */
    private $oModelProducts;

    /**
     * @var ProductsController
     */
    private $oController;

    /**
     * @var \Core\Library\View\JsonView
     */
    private $oView;

    private $aCategory = [];

    private $aProducts = [];

    public function setUp()
    {
        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = '';


        $this->oRequest = new MockProductRequest();
        $this->oModelCategories = new ModelCategories();
        $this->oModelProducts = new ModelProducts();
        $this->oModelCategories->truncate();
        $this->oModelProducts->truncate();

        $this->aCategory[] = $this->oModelCategories->create([
            'name' => 'Processors',
            'category_parent_id' => null
        ]);
        $this->aCategory[] = $this->oModelCategories->create([
            'name' => 'HDD',
            'category_parent_id' => null
        ]);
        $oRequest = $this->getMockForService(MockRequest::class, 'Request', ['get'], [], false);
        $oRequest->method('get')->will($this->returnValue(['name'=>'test2','status'=>'enable','category_id'=>$this->aCategory[0]->getKey(), 'price'=>1234]));
        $aProducts = [
            [
                'name'=>'LED LCD 18.5',
                'status'=>'enable',
                'category_id'=> $this->aCategory[0]->getKey(),
                'price' => '74'
            ],
            [
                'name'=>'LG 18,5',
                'status'=>'enable',
                'category_id'=> $this->aCategory[0]->getKey(),
                'price' => '82'
            ],
            [
                'name'=>'CPU LGA1150 Intel Pentium Dual Core G3240 (Haswell)',
                'status'=>'enable',
                'category_id'=> $this->aCategory[1]->getKey(),
                'price' => '45'
            ],
            [
                'name'=>'CPU LGA1150 Intel Core i3-4150 3.5GHz',
                'status'=>'enable',
                'category_id'=> $this->aCategory[1]->getKey(),
                'price' => '85'
            ]
        ];

        foreach($aProducts as $aProduct){
            $this->aProducts[] = $this->oModelProducts->create(
                $aProduct
            );
        }

        $this->oController = new ProductsController(
            $this->oModelCategories,
            $this->oModelProducts,
            $this->oRequest,
            new stubError(),
            new MockValidator()
        );
        $this->oView = new \Core\Library\View\JsonView();
        $this->oController->setView($this->oView);

    }


    public function testIndex()
    {
        $this->oRequest->set(['page'=>'1', 'sort'=>'name']);
        $this->oController->index();

        $oView = $this->oController->getView();
        $aVar = $oView->getVar('products')->toArray();
        $this->assertEquals('CPU LGA1150 Intel Core i3-4150 3.5GHz', $aVar['data']['0']['name']);
        $this->assertEquals('HDD', $aVar['data']['0']['category']['name']);
    }

    public function testAdd()
    {
        $this->oRequest->set('id', $this->aProducts[0]->getKey());
        $this->oController->add();
        $oView = $this->oController->getView();
        $aCategories = $oView->getVar('categories')->toArray();
        $aProduct = $oView->getVar('product');
        $this->assertEquals('HDD', $aCategories['1']['name']);
        $this->assertEquals('LED LCD 18.5', $aProduct['name']);
    }

    public function testCreate()
    {
        $this->oRequest->set('product',
            [
                'status' => 'enable',
                'category_id' => $this->aCategory[0]->getKey(),
                'price'=>123,
                'name' => 'test'
            ]
        );

        $this->oController->create();
        $oView = $this->oController->getView();
        $aResponse = $oView->getVar('response');
        $this->assertEquals('Product successfully added', $aResponse['message']);
        $this->assertEquals('test', $aResponse['product']->toArray()['name']);

        $this->oRequest->set('product', []);
        $this->oController->create();
        $this->expectOutputString('Provide all fields and enter correct data');
    }

    public function testEdit()
    {
        $this->oController->edit($this->aProducts[1]->getKey());
        $oView = $this->oController->getView();
        $aProduct = $oView->getVar('product')->toArray();
        $this->assertEquals('LED LCD 18.5', $aProduct['name']);
    }

    public function testUpdate()
    {
        $this->oController->update($this->aProducts[1]->getKey());
        $this->expectOutputString('');
        $oRequest = $this->getMockForService(MockRequest::class, 'Request', ['get'], [], false);
        $oRequest->method('get')->will($this->returnValue([]));
        $this->oController->update(null);
        $this->expectOutputString('Provide all fields');
    }

    public function testShow()
    {
        $this->oController->show($this->aProducts[1]->getKey());
        $oView = $this->oController->getView();
        $aProduct = $oView->getVar('product')->toArray();
        $this->assertEquals('LG 18,5', $aProduct['name']);
    }


    public function testDisable()
    {
        $this->oController->disable($this->aProducts[1]->getKey());
        $oView = $this->oController->getView();
        $this->oController->show($this->aProducts[1]->getKey());
        $this->assertEquals($oView->getVar('product')->toArray()['status'], 'disable');
        $this->oController->disable($this->aProducts[1]->getKey().'1');
        $this->expectOutputString('Can not disable the product');
    }

    public function testEnable()
    {

        $this->oController->enable($this->aProducts[1]->getKey());
        $oView = $this->oController->getView();
        $this->oController->show($this->aProducts[1]->getKey());
        $this->assertEquals($oView->getVar('product')->toArray()['status'], 'enable');
        ob_clean();
        $this->oController->enable($this->aProducts[0]['id'].'1');
        $this->expectOutputString('Can not enable the product');
    }

    public function testDelete()
    {
        $this->oController->delete(null);
        $this->expectOutputString('Unable delete product from database');
        ob_clean();
        $this->oController->delete($this->aProducts[0]->getKey());
        $this->expectOutputString('');
    }

    public function testMassDelete()
    {
        $this->oController->massDelete();
        $this->expectOutputString('Unable delete products from database');
        ob_clean();
        $this->oRequest->set('products',
            [
                $this->aCategory[0]->getKey(),
                $this->aCategory[1]->getKey()
            ]
        );
        $this->oController->massDelete();
        $this->expectOutputString('');
    }


}

class stubError extends Error
{

    public static function display($sMsg, $iErrCode = null, $sFormat = 'html')
    {
        echo $sMsg;
    }
}

class MockProductRequest extends Request{
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

class MockValidator extends Validator{

}