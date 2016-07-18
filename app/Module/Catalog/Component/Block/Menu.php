<?php
namespace Module\Core\Component\Block;

use Core\Library\Component\Block;
use Core\Library\Event\Dispatcher as EventDispatcher;

class Menu extends Block
{

    public function __construct(EventDispatcher $oEventDispatcher)
    {
        $this->_oEventDispatcher = $oEventDispatcher;
    }

    public function leftMenu()
    {
        //todo:: collect admin left menu
    }

    public function topMenu()
    {
        //todo::collect admin top menu items
    }

    public function process()
    {
        // TODO: Implement process() method.
    }
}