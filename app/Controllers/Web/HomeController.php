<?php

namespace Lyncas\Controllers\Web;

use Lyncas\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view->render(
            $this->response, 
            'home.html', 
            ['title' => 'Google Books Api']
        );
    }
}