<?php
use \Module\Catalog\Model\Categories as ModelCategories;
use \Module\Catalog\Model\Products as ModelProducts;
ModelProducts::truncate();
ModelCategories::truncate();
$mCategories[] = ModelCategories::create(
    [
        'name' => 'Monitors',
        'category_parent_id' => null
    ]
);
$mCategories[] = ModelCategories::create(
    [
        'name' => 'Processors',
        'category_parent_id' => null
    ]
);

ModelProducts::create([
    'name'=>'LED LCD 18.5',
    'status'=>'enable',
    'category_id'=> $mCategories[0]->getKey(),
    'price' => '74'
]);
ModelProducts::create([
    'name'=>'CPU LGA1150 Intel Pentium Dual Core G3240 (Haswell)',
    'status'=>'enable',
    'category_id'=> $mCategories[1]->getKey(),
    'price' => '45'
]);


$I = new AcceptanceTester($scenario);

$I->maximizeWindow();
$I->amOnPage('/catalog#/');
$I->see('Products');
$I->click('button[data-ui-sref="product-add"]');
$I->see('Add New');
//
//create feature
$I->appendField('input[name=product_name]', 'test');
$option = $I->grabTextFrom('select[ng-model="product.category_id"] option:nth-child(2)');
$I->selectOption("select", $option);
$I->appendField('input[name=product_price]', '123');
$I->click('button[data-ng-click="add()"]');
$I->waitForText('Product successfully added');
$I->see('test');
$I->appendField('#find', 'test');
$I->click('.input-group-btn button');
$I->see('test');

//update feature
$I->click('button[data-ui-sref="product-add({id:product.id})"]');
$I->appendField('input[name=product_name]', 'test2');
$option = $I->grabTextFrom('select[ng-model="product.category_id"] option:nth-child(1)');
$I->selectOption("select", $option);
$I->appendField('input[name=product_price]', '12');
$I->click('button[data-ng-click="update()"]');
$I->waitForText('Product successfully update');
$I->see('test2');

//disable feature
$I->click('i[data-ng-click="disable(product.id)"]');
$I->waitForText('Product successfully disable');
$I->wait(5);
//delete feature
$I->click('input[data-ng-click="checkAll()"]');
$I->click('#add-button .btn-danger');
$I->waitForText('Products successfully deleted');

//clear database
ModelProducts::truncate();
ModelCategories::truncate();