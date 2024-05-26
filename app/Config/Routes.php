<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    // AUTH SYSTEM
    $routes->get('invalid', 'AuthController::invalid');
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('profile', 'AuthController::profile', ['filter' => 'adminauth']);
    $routes->get('logout', 'AuthController::logout', ['filter' => 'adminauth']);

    //Company Info
    $routes->post('add-info', 'InfoController::addInfo', ['filter' => 'adminauth']);
    $routes->put('update-info/(:num)', 'InfoController::updateInfo/$1', ['filter' => 'adminauth']);
    $routes->delete('delete-info/(:num)', 'InfoController::deleteInfo/$1', ['filter' => 'adminauth']);

    //CarrerInfo
     $routes->post('add-carrer', 'CarrerController::addCarrer', ['filter' => 'adminauth']);
     $routes->put('update-carrer/(:num)', 'CarrerController::updateCarrer/$1', ['filter' => 'adminauth']);
     $routes->delete('delete-carrer/(:num)', 'CarrerController::deleteCarrer/$1', ['filter' => 'adminauth']);

     //HomeInfo
     $routes->post('add-home', 'HomeController::addHome', ['filter' => 'adminauth']);
     $routes->put('update-home/(:num)', 'HomeController::updateHome/$1', ['filter' => 'adminauth']);
     $routes->delete('delete-home/(:num)', 'HomeController::deleteHome/$1', ['filter' => 'adminauth']);


     //Carrer Register
     //User Authority
     $routes->post('register-carrer', 'RegisterCarrerController::addRegisterCarrer');
     //Admin Authority
     $routes->get('show-register-carrer', 'RegisterCarrerController::showRegisterCarrer', ['filter' => 'adminauth']);
     $routes->get('show-register-carrerbyid/(:num)', 'RegisterCarrerController::showRegisterCarrerById/$1', ['filter' => 'adminauth']);
     $routes->delete('delete-register-carrer/(:num)', 'RegisterCarrerController::deleteRegisterCarrer/$1', ['filter' => 'adminauth']);

     //Home Register
     //User Authority
     $routes->post('register-home', 'RegisterHomeController::addRegisterHome');
     //Admin Authority
     $routes->get('show-register-home', 'RegisterHomeController::showRegisterHome', ['filter' => 'adminauth']);
     $routes->get('show-register-homebyid/(:num)', 'RegisterHomeController::showRegisterHomeById/$1', ['filter' => 'adminauth']);
     $routes->delete('delete-register-home/(:num)', 'RegisterHomeController::deleteRegisterHome/$1', ['filter' => 'adminauth']);

     //ReadAll Informatin Anonymuse
     $routes->get('show-allcarrer', 'CarrerController::showCarrer');
     $routes->get('show-carrerbyid/(:num)', 'CarrerController::showCarrerById/$1');

     $routes->get('show-home', 'HomeController::showHome');
     $routes->get('show-homebyid/(:num)', 'HomeController::showHomeById/$1');

     $routes->get('show-allinfo', 'InfoController::showAllInfo');
     $routes->get('show-infobyid/(:num)', 'InfoController::showInfoById/$1');
    });
