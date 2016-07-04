<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';
////tungns: blogs
//$route['blogs'] = 'blogs';
//
//$route['blogs/detail'] = 'blogs/detail';
//
//$route['blogs/detail/(:any)'] = "blogs/detail/$1";
//
//$route['blogs/addnew'] = 'blogs/addnew';
//
//$route['blogs/delete'] = 'blogs/delete';
//
//$route['blogs/edit'] = 'blogs/edit';
//
//
////tungns: code developer 
//$route['developer'] = 'developer';
//$route['developer/php'] = 'developer/php';
//$route['developer/java'] = 'developer/java';
//$route['developer/js'] = 'developer/js';
//$route['developer/db'] = 'developer/db';
//
////tungns: config private
//$route['do_purchase'] = 'paypal/do_purchase';
//$route['returnpage'] = 'paypal/returnpage';
//
//$route['paypal/pay'] = 'paypal/pay';
//$route['ipn'] = 'paypal/ipn';
//$route['cancel'] = 'paypal/cancel';
//
//$route['editProduct'] = 'product/editProduct';
//$route['deleteImages'] = 'product/deleteImages';
//$route['detailProduct'] = 'product/detailProduct';
//$route['logout'] = 'home/logout';
//$route['login'] = 'home/login';
//$route['createProduct'] = 'product/createProduct';
//$route['register'] = 'home/register';
//$route['test'] = 'test/demo';
//$route['addProduct'] = 'home/addProduct';
//$route['addToCart'] = 'home/addToCart';
//$route['removeFromCart'] = 'home/removeFromCart';
//$route['changeNumberOrder'] = 'home/changeNumberOrder';
//$route['checkoutCart'] = 'home/checkoutCart';
//$route['(:any)'] = "home/product_lookup/$1";
/* End of file routes.php */
/* Location: ./application/config/routes.php */