<?php
namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\FacultyModel;
use App\Models\StudentModel;
use App\Models\AcademicModel;  
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function loginForm(): string
    {
        return view('index');  // The login page (index.php)
    }

    public function login()
    {
        // Get the POST data from the form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $userType = $this->request->getPost('user_type');

        // Initialize model and redirect URL based on user type
        $userModel = null;
        $redirectUrl = '/';

        // Choose model based on the user type (admin, student, or faculty)
        switch ($userType) {
            case 'admin':
                $userModel = new AdminModel();  // Admin table
                $redirectUrl = '/admin/dashboard';  // Redirect to Admin dashboard
                break;
            case 'student':
                $userModel = new StudentModel();  // Student table
                $redirectUrl = '/student/dashboard';  // Redirect to Student dashboard
                break;
            case 'faculty':
                $userModel = new FacultyModel();  // Faculty table
                $redirectUrl = '/faculty/dashboard';  // Redirect to Faculty dashboard
                break;
            default:
                session()->setFlashdata('error', 'Invalid user type selected.');
                return redirect()->back();
        }

        // Check if the email exists in the corresponding table
        $user = $userModel->where('email', $email)->first();

        // If user exists, check the password and account activation status
        if ($user && password_verify($password, $user['passwordHash'])) {
            // Check if faculty account is active
            if ($userType == 'faculty' && $user['is_active'] == 0) {
                // If the faculty's account is not activated
                session()->setFlashdata('error', 'Your account is not activated yet. Please wait for admin approval.');
                return redirect()->back();
            }

            // Retrieve the active academic session
            $academicModel = new AcademicModel();
            $activeAcademicSession = $academicModel->where('status', 1)->first(); // Get active academic session

            if ($activeAcademicSession) {
                // Store session data including academic_id
                session()->set([
                    'isLoggedIn' => true,
                    'userType' => $userType,
                    'userId' => $user['id'],
                    'userEmail' => $user['email'],
                    'full_name' => $user['full_name'],
                    'academic_id' => $activeAcademicSession['id'],  // Store the academic_id
                ]);

                // Check if the user is faculty and set faculty-specific session data
                if ($userType == 'faculty') {
                    session()->set('faculty_id', $user['id']);  // Store faculty_id for faculty-specific actions
                }

                // Redirect to the respective dashboard
                return redirect()->to($redirectUrl);
            } else {
                // If no active academic session found, show error message
                session()->setFlashdata('error', 'Your academic status is not valid for login. Please check with the admin.');
                return redirect()->back();
            }
        }

        // If login fails, show an error message
        session()->setFlashdata('error', 'Invalid email or password.');
        return redirect()->back();
    }
}
