<?php

Gundi()->Router->resource('/catalog/products', Module\Catalog\Component\Controller\Products::class);
Gundi()->Router->resource('/catalog/categories', Module\Catalog\Component\Controller\Categories::class);
Gundi()->Router->get('/catalog/productEnable/(:num)', '\Module\Catalog\Component\Controller\Products@enable');
Gundi()->Router->get('/catalog/productDisable/(:num)', '\Module\Catalog\Component\Controller\Products@disable');
Gundi()->Router->post('/catalog/productMassDelete', '\Module\Catalog\Component\Controller\Products@massDelete');