<?php
use \Module\Catalog\Model\Categories as ModelCategories;
use \Module\Catalog\Model\Products as ModelProducts;
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

$I = new AcceptanceTester($scenario);

$I->maximizeWindow();
$I->amOnPage('/catalog#/categories');
$I->see('Categories');
$I->click('button[data-ui-sref="category-add"]');
$I->see('Add New');
//
//create feature
$I->appendField('input[name=category_name]', 'test');
$option = $I->grabTextFrom('select[ng-model="category.category_parent_id"] option:nth-child(1)');
$I->selectOption("select", $option);
$I->click('button[data-ng-click="add()"]');
$I->waitForText('Category successfully added');
$I->see('test');

////update feature
$I->click('button[data-ui-sref="category-add({id:category.id})"]');
$I->appendField('input[name=category_name]', 'myCategory');
$option = $I->grabTextFrom('select[ng-model="category.category_parent_id"] option:nth-child(2)');
$I->selectOption("select", $option);
$I->click('button[data-ng-click="update()"]');
$I->waitForText('Category successfully update');
$I->see('myCategory');

//delete feature
$I->click('.btn-danger');
$I->waitForText('Category successfully deleted');

////clear database
ModelCategories::truncate();