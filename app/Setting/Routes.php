<?php

/** If no route found. */
Gundi()->Router->error('\Module\Core\Component\Controller\DisplayErr404@index');

Gundi()->Theme->setLayout('index');
Gundi()->Router->get('', '\Module\Catalog\Component\Controller\Index@index');
