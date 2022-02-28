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
$route['default_controller'] = 'home';
$route['404_override'] = '';

// home
$route['translate_uri_dashes'] = FALSE;
$route['insert'] = 'home/insert';
$route['view/:num'] = 'home/view/$1';
$route['forgot/(:any)/(:any)/(:any)'] = 'home/forgot/$1/$2/$3';
$route['forgotpassword'] = 'home/forgotpassword';
$route['resend'] = 'home/resend';
$route['auth/(:any)'] = 'auth/$1';


// doctors
// change pass
$route['doctor/changepassword'] = 'doctor/changepassword';
$route['doctor/changepassword2'] = 'doctor/changepassword2';
// inquiries
$route['doctor/inquiries'] = 'doctor/inquiries';
$route['doctor/inquiries_list'] = 'doctor/inquiries_list';
// chatbox
$route['doctor/messages'] = 'doctor/messages';
$route['doctor/send'] = 'doctor/send';
$route['doctor/getChats'] = 'doctor/getChats';
$route['doctor/getNameandActive/(:any)'] = 'doctor/getNameandActive/$1';
//consultation
$route['doctor/consultation/(:num)'] = 'doctor/consultation/$1';
$route['doctor/sendlink'] = 'doctor/sendlink';
$route['doctor/addPrescription'] = 'doctor/addPrescription';
$route['doctor/cancelConsultation/(:num)'] = 'doctor/cancelConsultation/$1';
$route['doctor/doneConsultation/(:num)'] = 'doctor/doneConsultation/$1';
//transaction
$route['doctor/transaction/(:num)'] = 'doctor/transaction/$1';
//view immunization 
$route['doctor/immunizationrecord/(:num)'] = 'doctor/immunizationrecord/$1';
$route['doctor/addImmunizationRecord/(:num)'] = 'doctor/addImmunizationRecord/$1';
$route['doctor/editImmunizationRecord/(:num)'] = 'doctor/editImmunizationRecord/$1';
$route['doctor/deleteImmunizationRecord/(:num)'] = 'doctor/deleteImmunizationRecord/$1';
// parents
$route['doctor/parents/(:num)'] = 'doctor/parents/$1';
$route['doctor/addParents'] = 'doctor/addParents';
// patients
$route['doctor/patients/(:num)'] = 'doctor/patients/$1';
$route['doctor/addChild/(:num)'] = 'doctor/addChild/$1';
$route['doctor/editChild/(:num)'] = 'doctor/editChild/$1';
$route['doctor/deleteChild/(:num)'] = 'doctor/deleteChild/$1';
// view immunization 
$route['doctor/viewImmunizationRecord/(:num)'] = 'doctor/viewImmunizationRecord/$1';
// announcements
$route['doctor/editAnnouncements/(:num)'] = 'doctor/editAnnouncements/$1';
$route['doctor/deleteAnnouncements/(:num)'] = 'doctor/deleteAnnouncements/$1';
$route['doctor/viewAnnouncements/(:num)'] = 'doctor/viewAnnouncements/$1';
$route['doctor/announcements/(:num)'] = 'doctor/announcements/$1';

// patient satisfaction
$route['doctor/editPatientSatisfaction/(:num)'] = 'doctor/editPatientSatisfaction/$1';
$route['doctor/deletePatientSatisfaction/(:num)'] = 'doctor/deletePatientSatisfaction/$1';
$route['doctor/viewPatientSatisfaction/(:num)'] = 'doctor/viewPatientSatisfaction/$1';
$route['doctor/patient_satisfaction/(:num)'] = 'doctor/patient_satisfaction/$1';

// logout
$route['doctor/logout'] = 'doctor/logout';


// receptionist
// change pass
$route['receptionist/changepassword'] = 'receptionist/changepassword';
$route['receptionist/changepassword2'] = 'receptionist/changepassword2';
// inquiries
$route['receptionist/inquiries'] = 'receptionist/inquiries';
$route['receptionist/inquiries_list'] = 'receptionist/inquiries_list';
// chatbox
$route['receptionist/messages'] = 'receptionist/messages';
$route['receptionist/send'] = 'receptionist/send';
$route['receptionist/getChats'] = 'receptionist/getChats';
$route['receptionist/getNameandActive/(:any)'] = 'receptionist/getNameandActive/$1';
//consultation
$route['receptionist/consultation/(:num)'] = 'receptionist/consultation/$1';
$route['receptionist/sendlink'] = 'receptionist/sendlink';
$route['receptionist/addPrescription'] = 'receptionist/addPrescription';
$route['receptionist/cancelConsultation/(:num)'] = 'receptionist/cancelConsultation/$1';
$route['receptionist/doneConsultation/(:num)'] = 'receptionist/doneConsultation/$1';
//transaction
$route['receptionist/transaction/(:num)'] = 'receptionist/transaction/$1';
//view immunization 
$route['receptionist/immunizationrecord/(:num)'] = 'receptionist/immunizationrecord/$1';
$route['receptionist/addImmunizationRecord/(:num)'] = 'receptionist/addImmunizationRecord/$1';
$route['receptionist/editImmunizationRecord/(:num)'] = 'receptionist/editImmunizationRecord/$1';
$route['receptionist/deleteImmunizationRecord/(:num)'] = 'receptionist/deleteImmunizationRecord/$1';
// parents
$route['receptionist/parents/(:num)'] = 'receptionist/parents/$1';
$route['receptionist/addParents'] = 'receptionist/addParents';
// patients
$route['receptionist/patients/(:num)'] = 'receptionist/patients/$1';
$route['receptionist/addChild/(:num)'] = 'receptionist/addChild/$1';
$route['receptionist/editChild/(:num)'] = 'receptionist/editChild/$1';
$route['receptionist/deleteChild/(:num)'] = 'receptionist/deleteChild/$1';
// view immunization 
$route['receptionist/viewImmunizationRecord/(:num)'] = 'receptionist/viewImmunizationRecord/$1';
// logout
$route['receptionist/logout'] = 'receptionist/logout';

// administrator
// change pass
$route['administrator/changepassword'] = 'administrator/changepassword';
$route['administrator/changepassword2'] = 'administrator/changepassword2';
// inquiries
$route['administrator/inquiries'] = 'administrator/inquiries';
$route['administrator/inquiries_list'] = 'administrator/inquiries_list';
// chatbox
$route['administrator/messages'] = 'administrator/messages';
$route['administrator/send'] = 'administrator/send';
$route['administrator/getChats'] = 'administrator/getChats';
$route['administrator/getNameandActive/(:any)'] = 'administrator/getNameandActive/$1';

//consultation
$route['administrator/consultation/(:num)'] = 'administrator/consultation/$1';
$route['administrator/sendlink'] = 'administrator/sendlink';
$route['administrator/addPrescription'] = 'administrator/addPrescription';
$route['administrator/cancelConsultation/(:num)'] = 'administrator/cancelConsultation/$1';
$route['administrator/doneConsultation/(:num)'] = 'administrator/doneConsultation/$1';
//transaction
$route['administrator/transaction/(:num)'] = 'administrator/transaction/$1';
//view immunization 
$route['administrator/immunizationrecord/(:num)'] = 'administrator/immunizationrecord/$1';
$route['administrator/addImmunizationRecord/(:num)'] = 'administrator/addImmunizationRecord/$1';
$route['administrator/editImmunizationRecord/(:num)'] = 'administrator/editImmunizationRecord/$1';
$route['administrator/deleteImmunizationRecord/(:num)'] = 'administrator/deleteImmunizationRecord/$1';
// parents
$route['administrator/parents/(:num)'] = 'administrator/parents/$1';
$route['administrator/addParents'] = 'administrator/addParents';
// patients
$route['administrator/patients/(:num)'] = 'administrator/patients/$1';
$route['administrator/addChild/(:num)'] = 'administrator/addChild/$1';
$route['administrator/editChild/(:num)'] = 'administrator/editChild/$1';
$route['administrator/deleteChild/(:num)'] = 'administrator/deleteChild/$1';
// view immunization 
$route['administrator/viewImmunizationRecord/(:num)'] = 'administrator/viewImmunizationRecord/$1';
// access privilege
$route['administrator/accessprivilege/(:num)'] = 'administrator/accessprivilege/$1';
$route['administrator/addAccessPrivilege/(:num)/(:num)'] = 'administrator/addAccessPrivilege/$1/$2';
// logout
$route['administrator/logout'] = 'administrator/logout';