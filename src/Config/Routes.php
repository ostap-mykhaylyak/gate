<?php

namespace Ostap\Gate\Config;

use CodeIgniter\Router\RouteCollection;

class Routes
{
    public static function routes(RouteCollection $routes)
    {
        $routes->get('login', 'Ostap\Gate\Controllers\Auth::login');
    }
}
