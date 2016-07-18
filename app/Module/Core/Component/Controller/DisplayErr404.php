<?php

namespace Module\Core\Component\Controller;

use Core\Library\Component\Controller;

class DisplayErr404 extends Controller
{

    /**
     * Index page is a error 404
     */

    public function index()
    {
        header(Gundi()->Url->getHeaderCode(404));
    }

}
