<?php
namespace Module\Catalog\Database\Seed;

use Core\Library\Database\Seeder;
use Module\Catalog\Model\Categories;
use Module\Catalog\Model\Products;

Class Catalog_1_0_0 extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * clear
         */
        Categories::truncate();
        Products::truncate();
        $mCategories[] = Categories::create(
            [
                'name' => 'Monitors',
                'category_parent_id' => null
            ]
        );
        $mCategories[] = Categories::create(
            [
                'name' => 'Processors',
                'category_parent_id' => null
            ]
        );
        $mCategories[] = Categories::create(
            [
                'name' => 'HDD',
                'category_parent_id' => null
            ]
        );
        $mCategories[] = Categories::create(
            [
                'name' => 'SSD',
                'category_parent_id' => $mCategories[2]->getKey()
            ]
        );
        $aProducts = [
            [
                'name'=>'LED LCD 18.5',
                'status'=>'enable',
                'category_id'=> $mCategories[0]->getKey(),
                'price' => '74'
            ],
            [
                'name'=>'LG 18,5',
                'status'=>'enable',
                'category_id'=> $mCategories[0]->getKey(),
                'price' => '82'
            ],
            [
                'name'=>'CPU LGA1150 Intel Pentium Dual Core G3240 (Haswell)',
                'status'=>'enable',
                'category_id'=> $mCategories[1]->getKey(),
                'price' => '45'
            ],
            [
                'name'=>'CPU LGA1150 Intel Core i3-4150 3.5GHz',
                'status'=>'enable',
                'category_id'=> $mCategories[1]->getKey(),
                'price' => '85'
            ],
            [
                'name'=>'HDD 500GB, Toshiba',
                'status'=>'enable',
                'category_id'=> $mCategories[2]->getKey(),
                'price' => '45'
            ],
            [
                'name'=>'Seagate 1TB 7200rpm 64MB Baracuda SATAII/SATAIII',
                'status'=>'enable',
                'category_id'=> $mCategories[2]->getKey(),
                'price' => '55.90'
            ]
        ];

        foreach($aProducts as $aProduct){
            Products::create(
                $aProduct
            );
        }
    }

}