<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Dashboard::index');
$routes->get('admin', 'Admin::index', ['filter' => 'role:SuperAdmin'], ['as' => 'admin']);
$routes->get('admin/index', 'Admin::index', ['filter' => 'role:SuperAdmin']);
$routes->get('admin/(:num)', 'Admin::detail/$1', ['filter' => 'role:SuperAdmin'], ['as' => 'detail']);
$routes->post('updateUser', 'Admin::updateUser', ['filter' => 'role:SuperAdmin'], ['as' => 'updateUser']);

$routes->get('filemanager', 'FileManager::index', ['as' => 'filemanager']);
$routes->get('uploadAttachment/(:alphanum)/(:num)', 'FileManager::uploadAttachment/$1/$2');
$routes->post('uploadFile', 'FileManager::uploadFile', ['filter' => 'role:SuperAdmin,AdminClient'], ['as' => 'uploadFile']);
$routes->get('toDownload/(:num)/', 'FileManager::toDownload/$1');
$routes->delete('/deleteFile/(:num)', 'Filemanager::deleteFile/$1', ['filter' => 'role:SuperAdmin']);

$routes->get('createForum/(:num)', 'Forum::create/$1');
$routes->post('saveForum', 'Forum::saveForum');
$routes->get('detailForum/(:num)', 'Forum::detailForum/$1');
$routes->get('editForum/(:num)', 'Forum::editForum/$1');
$routes->post('updateForum/(:num)', 'Forum::updateForum/$1');
$routes->delete('deleteForum/(:num)', 'Forum::deleteForum/$1');
$routes->get('forumAttDownload/(:any)', 'Forum::forumAttDownload/$1');
$routes->delete('deleteForumAtt/(:num)', 'Forum::deleteForumAtt/$1');

$routes->get('createReply/(:num)', 'Forum::createReply/$1');
$routes->post('saveReply', 'Forum::saveReply');
$routes->get('editReply/(:num)', 'Forum::editReply/$1');
$routes->post('updateReply', 'Forum::updateReply');
$routes->delete('deleteReply/(:num)', 'Forum::deleteReply/$1');


$routes->add('/addSegment', 'Dashboard::addSegment', ['filter' => 'role:SuperAdmin']);
$routes->post('editSegment', 'Dashboard::editSegment', ['filter' => 'role:SuperAdmin']);
$routes->post('/addUnit', 'Dashboard::addUnit', ['filter' => 'role:SuperAdmin']);
$routes->post('/editUnit', 'Dashboard::editUnit', ['filter' => 'role:SuperAdmin']);
$routes->post('/checkInput', 'Dashboard::checkInput');
$routes->delete('/delete/(:any)/(:any)', 'Dashboard::delete/$1/$2', ['filter' => 'role:SuperAdmin,AdminClient']);

$routes->add('/unitDetail/(:num)/(:num)', 'Unit::index/$1/$2');
$routes->add('/addJob/(:num)/(:num)', 'Unit::addJob/$1/$2', ['filter' => 'role:SuperAdmin']);

$routes->add('jobDetail/(:num)/(:num)', 'Job::index/$1/$2');
$routes->post('/editJob', 'Job::editJob', ['filter' => 'role:SuperAdmin']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
