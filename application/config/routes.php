<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'connect/index';
$route['404_override'] = '';

$route['cong-ket-noi-tieng-cuoi'] = "connect";
$route['tham-gia-ket-noi-tieng-cuoi'] = "connect/connectJoin";
$route['thu-vien-tieng-cuoi']                 = "smile/stepOne";
$route['chinh-sua-clip/(:num)/(:num)']        = "smile/stepTwo";
$route['clip-trao-nhau-tieng-cuoi-tet']       = "smile/index";
$route['chinh-sua-clip-buoc-1/(:num)']        = "smile/editVideoStepOne";
$route['chinh-sua-clip-buoc-2/(:num)']        = "smile/editVideoStepTwo";
$route['chinh-sua-clip-buoc-3/(:num)/(:num)'] = "smile/editVideoStepThree";
$route['admincp'] = "admincp";
$route['admincp/menu'] = "admincp/menu";
$route['admincp/statistics'] = "admincp/statistics";
$route['admincp/login'] = "admincp/login";
$route['admincp/logout'] = "admincp/logout";
$route['admincp/permission'] = "admincp/permission";
$route['admincp/saveLog'] = "admincp/saveLog";
$route['admincp/update_profile'] = "admincp/update_profile";
$route['admincp/setting'] = "admincp/setting";
$route['admincp/post_security'] = "admincp/post_security";
$route['admincp/getSetting'] = "admincp/getSetting";
$route['admincp/(:any)/(:any)/(:any)/(:any)'] = "$1/admincp_$2/$3/$4";
$route['admincp/(:any)/(:any)/(:any)'] = "$1/admincp_$2/$3";
$route['admincp/(:any)/(:any)'] = "$1/admincp_$2";
$route['admincp/(:any)'] = "$1/admincp_index";

$route['translate_uri_dashes'] = FALSE;

