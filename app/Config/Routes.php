<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentication routes for login, logout, and dashboards
$routes->group('', ['namespace' => 'App\Controllers'], function($routes) {
    // Login and logout routes
    $routes->get('login', 'AuthController::loginForm');  // Show login form
    $routes->post('auth/login', 'AuthController::login');  // Handle login POST request
    $routes->get('logout', 'AuthController::logout');  // Handle logout
    // User dashboards (admin, student, faculty)
    $routes->get('admin/dashboard', 'AdminAuthController::index', ['filter' => 'auth:admin']); // Admin dashboard
    $routes->get('student/dashboard', 'StudentAuthController::index', ['filter' => 'auth:student']); // Student dashboard
    $routes->get('faculty/dashboard', 'FacultyAuthController::index', ['filter' => 'auth:faculty']); // Faculty dashboard
});

// Admin Routes
$routes->group('admin', function($routes) {
    // Admin authentication and registration
    $routes->get('sign_up', 'AdminAuthController::sign_up'); // Admin sign-up form
    $routes->post('register', 'AdminAuthController::register'); // Admin registration process
    $routes->get('verifyEmail/(:any)', 'AdminAuthController::verifyEmail/$1'); // Admin email verification

    // College and Department Routes
    $routes->get('college', 'AdminAuthController::collegeForm'); // Admin form to add colleges
    $routes->post('saveCollege', 'AdminAuthController::saveCollege'); // Save new college
    $routes->get('getColleges', 'AdminAuthController::getColleges'); // Get all colleges
    // $routes->post('updateCollege/(:num)', 'AdminAuthController::updateCollege/$1'); // Update college by ID
    // $routes->delete('deleteCollege/(:num)', 'AdminAuthController::deleteCollege/$1'); // Delete college by ID
    
    $routes->get('department', 'AdminAuthController::deptForm'); // Department form
    $routes->post('saveDepartment', 'AdminAuthController::saveDepartment'); // Save new department
    $routes->get('getDepartments', 'AdminController::getDepartments');


});

$routes->group('faculty', function($routes) {
    $routes->get('sign_up', 'FacultyAuthController::sign_up');
    $routes->post('register', 'FacultyAuthController::register');
    $routes->get('verifyEmail/(:any)', 'FacultyAuthController::verifyEmail/$1');
    $routes->get('waiting_for_approval', 'FacultyAuthController::waiting_for_approval');
    $routes->get('approval_success', 'FacultyAuthController:: approval_success');
    $routes->get('getDepartments/(:num)', 'FacultyAuthController::getDepartments/$1');

    // Faculty list and actions
    $routes->get('list', 'FacultyAuthController::faculty_list');
    $routes->get('getFacultyList', 'FacultyAuthController::getFacultyList');
    $routes->post('activate/(:num)', 'FacultyAuthController::activate/$1');
    $routes->post('deactivate/(:num)', 'FacultyAuthController::deactivate/$1');
    $routes->post('delete/(:num)', 'FacultyAuthController::delete/$1');
    $routes->get('editFaculty/(:num)', 'FacultyAuthController::editFaculty/$1'); // Edit Faculty
    $routes->post('updateFaculty/(:num)', 'FacultyAuthController::updateFaculty/$1'); // Update Faculty
});

$routes->group('student', function($routes) {
    $routes->get('sign_up', 'StudentAuthController::sign_up');
    $routes->post('register', 'StudentAuthController::register');
    $routes->get('verifyEmail/(:any)', 'StudentAuthController::verifyEmail/$1');

    // Faculty list and actions
    $routes->get('list', 'StudentAuthController::student_list');
    $routes->post('getStudentList', 'StudentAuthController::getStudentList');
    $routes->post('delete/(:num)', 'StudentAuthController::delete/$1');

});


$routes->group('evaluation', function($routes) {
    // Academic Routes
    $routes->get('academic', 'EvaluationController::academicForm');
    $routes->post('saveAcademic', 'EvaluationController::saveAcademic');
    $routes->get('startAcademic/(:num)', 'EvaluationController::startAcademic/$1');
    $routes->get('closeAcademic/(:num)', 'EvaluationController::closeAcademic/$1');

    // Rating Routes
    $routes->get('rating', 'EvaluationController::ratingForm');
    $routes->post('saveRating', 'EvaluationController::saveRating'); 

    // Criteria Routes
    $routes->get('criteria', 'EvaluationController::criteriaForm');
    $routes->post('saveCriteria', 'EvaluationController::saveCriteria');

    // Evaluation Question Routes
    $routes->get('evaluation_question', 'EvaluationController::evaluationQuestionForm');
    $routes->post('saveEvaluationQuestion', 'EvaluationController::saveEvaluationQuestion');

    // Evaluation Answer Routes
    $routes->get('form', 'EvaluationAnswerController::index'); // Access the evaluation form
    $routes->match(['get', 'post'], 'submit', 'EvaluationAnswerController::submit');

    //$routes->get('success', 'EvaluationAnswerController::success');  // Show confirmation after submission
});




