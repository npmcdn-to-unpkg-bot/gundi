<?php

/** If no route found. */
Gundi()->Router->error('\Module\Core\Component\Controller\DisplayErr404@index');

Gundi()->Theme->setLayout('index');
Gundi()->Router->get('', '\Module\Catalog\Component\Controller\Index@index');

Gundi()->Block->add('admin_left_menu', '*', 'Module\Core\Component\Block\Menu@leftMenu');
Gundi()->Block->add('admin_top_menu', '*', 'Module\Core\Component\Block\Menu@topMenu');
