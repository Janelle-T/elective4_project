<?php

namespace App\Controllers;

use App\Models\FacultyModel;
use App\Models\CollegeModel;
use App\Models\DepartmentModel;
use CodeIgniter\Controller;

class FacultyAuthController extends Controller
{
    public function __construct()
    {
        helper(['form']);
    }
    public function index()
    {
        return view('faculty/faculty_dash'); // Ensure this view exists
    }

    // Faculty registration form view
    public function sign_up()
    {
        $collegeModel = new CollegeModel();
        $colleges = $collegeModel->findAll();

        return view('faculty/faculty_form', ['colleges' => $colleges]);
    }

    // Faculty registration processing
    public function register()
    {
        $validation = \Config\Services::validation();

        // Define validation rules
        $validation->setRules([
            'faculty_id' => 'required|is_unique[faculty_list.faculty_id]',
            'full_name' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[faculty_list.email]',
            'password' => 'required|min_length[8]',
            'department' => 'required',
            'college' => 'required',
            
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Redirect back with errors if validation fails
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        

        // Prepare faculty data for insertion
        $facultyModel = new FacultyModel();
        $data = [
            'faculty_id' => $this->request->getPost('faculty_id'),
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'phoneNumber' => $this->request->getPost('phoneNumber'),
            'email' => $this->request->getPost('email'),
            'passwordHash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'college' => $this->request->getPost('college'),
            'department' => $this->request->getPost('department'),
            'is_active' => 0, // Faculty is inactive by default
            'email_verified' => 0, // Not verified initially
            'verification_token' => bin2hex(random_bytes(32)),
            
        ];

        // Insert faculty into the database
        if ($facultyModel->insert($data)) {
            // Send email verification link
            $emailService = \Config\Services::email();
            $emailService->setTo($data['email']);
            $emailService->setSubject('Email Verification');
            $message = 'Please click the link below to verify your email address: ' . base_url('faculty/verifyEmail/' . $data['verification_token']);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Registration successful! Please verify your email. A verification link has been sent.');
            } else {
                log_message('error', 'Failed to send verification email to: ' . $data['email']);
                session()->setFlashdata('error', 'Failed to send verification email.');
            }

            return redirect()->to('/faculty/sign_up'); // Redirect back to registration page
        } else {
            session()->setFlashdata('error', 'Failed to register faculty.');
            return redirect()->to('/faculty/sign_up'); // Redirect back to registration page
        }
    }

    // Fetch departments for a given college
    public function getDepartments($collegeId)
    {
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->getDepartmentsByCollege($collegeId);

        return $this->response->setJSON($departments); // Send departments as a JSON response
    }

    // Faculty email verification
    public function verifyEmail($token)
    {
        $facultyModel = new FacultyModel();
        // Search for faculty using the verification token
        $faculty = $facultyModel->where('verification_token', $token)->first();

        if ($faculty) {
            // Update faculty record to mark email as verified and clear the token
            $facultyModel->update($faculty['id'], ['email_verified' => 1, 'verification_token' => null]);

            // Log the successful email verification
            log_message('info', 'Faculty email verified successfully for: ' . $faculty['full_name']);

            // Set flash message for success and redirect to a page that indicates next steps (such as waiting for admin approval)
            session()->setFlashdata('success', 'Email Verified Successfully! Please wait for the Admin to activate your account.');
            return redirect()->to('/faculty/waiting_for_approval'); // Redirect to a waiting page
        }

        // Log the failure case
        log_message('error', 'Email verification failed for token: ' . $token);

        // Set flash message for failure and redirect back to the login page
        session()->setFlashdata('error', 'Email verification failed. Please request a new verification email.');
        return redirect()->to('/login');
    }

    public function waiting_for_approval()
    {
        return view('faculty/waiting_for_approval');
    }

    public function activate($id)
    {
        $facultyModel = new FacultyModel();
        $faculty = $facultyModel->find($id); // Fetch faculty details

        // Check if faculty exists
        if (!$faculty) {
            return json_encode(['success' => false, 'message' => 'Faculty not found']);
        }

        // Prepare email
        $emailService = \Config\Services::email();
        $emailService->setTo($faculty['email']);
        $emailService->setSubject('Your Faculty Account Has Been Activated');

        // Email message content
        $message = 'Dear ' . $faculty['full_name'] . ',<br><br>';
        $message .= 'Your faculty account has been successfully activated. You can now log in and access your account.<br>';
        $message .= 'Click the link below to log in:<br>';
        $message .= '<a href="' . base_url('login') . '">Go to Login Page</a><br><br>';
        $message .= 'Thank you for your patience.<br><br>';
        $message .= 'Best regards,<br>';
        $message .= 'Your University Admin Team';

        // Set the message body for the email and specify that it's HTML content
        $emailService->setMessage($message);
        $emailService->setMailType('html'); // Ensure the email is sent as HTML

        // Update faculty status to active
        $data = ['is_active' => 1];

        // Update the faculty account in the database
        if ($facultyModel->update($id, $data)) {
            // Send the activation email
            if ($emailService->send()) {
                return json_encode(['success' => true, 'message' => 'Faculty activated successfully and email sent.']);
            } else {
                // Log error if email sending fails
                log_message('error', 'Failed to send activation email to: ' . $faculty['email']);
                return json_encode(['success' => false, 'message' => 'Faculty activated successfully, but failed to send email.']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Failed to activate faculty']);
        }
    }



    public function deactivate($id)
    {
        $facultyModel = new FacultyModel();
        $faculty = $facultyModel->find($id); // Fetch faculty details

        // Check if faculty exists
        if (!$faculty) {
            return json_encode(['success' => false, 'message' => 'Faculty not found']);
        }

        // Prepare email
        $emailService = \Config\Services::email();
        $emailService->setTo($faculty['email']);
        $emailService->setSubject('Your Faculty Account Has Been Deactivated');

        // Email message content
        $message = 'Dear ' . $faculty['full_name'] . ',<br><br>';
        $message .= 'We regret to inform you that your faculty account has been deactivated by the admin.<br>';
        $message .= 'Please contact the admin if you have any questions or concerns.<br><br>';
        $message .= 'Best regards,<br>';
        $message .= 'Your University Admin Team';

        // Set the message body for the email and specify that it's HTML content
        $emailService->setMessage($message);
        $emailService->setMailType('html'); // Ensure the email is sent as HTML

        // Update faculty status to inactive
        $data = ['is_active' => 0];

        // Update the faculty account in the database
        if ($facultyModel->update($id, $data)) {
            // Send the deactivation email
            if ($emailService->send()) {
                return json_encode(['success' => true, 'message' => 'Faculty deactivated successfully and email sent.']);
            } else {
                // Log error if email sending fails
                log_message('error', 'Failed to send deactivation email to: ' . $faculty['email']);
                return json_encode(['success' => false, 'message' => 'Faculty deactivated successfully, but failed to send email.']);
            }
        } else {
            return json_encode(['success' => false, 'message' => 'Failed to deactivate faculty']);
        }
    }



    public function faculty_list()
    {
        return view('admin/faculty_list'); // Ensure this view exists
    }

    public function getFacultyList()
    {
        $facultyModel = new FacultyModel();
        $facultyList = $facultyModel->findAll();

        $collegeModel = new CollegeModel();
        $departmentModel = new DepartmentModel();

        $data = [];
        foreach ($facultyList as $index => $faculty) {
            // Fetch College Name
            $college = $collegeModel->find($faculty['college']);
            $collegeName = $college ? $college['college_name'] : 'N/A';

            // Fetch Department Name
            $department = $departmentModel->find($faculty['department']);
            $departmentName = $department ? $department['department_name'] : 'N/A';

            $data[] = [
                'counter' => $index + 1,
                'faculty_id' => $faculty['faculty_id'],
                'full_name' => $faculty['full_name'],
                'phone_number' => $faculty['phoneNumber'],
                'gender' => $faculty['gender'],
                'email' => $faculty['email'],
                'college' => $collegeName,
                'department' => $departmentName,
                'is_active' => $faculty['is_active'] ? 'Active' : 'Inactive',
                'actions' => '
                    <button onclick="editFaculty(' . $faculty['id'] . ')" class="btn btn-warning">Edit</button>
                    <button onclick="deleteFaculty(' . $faculty['id'] . ')" class="btn btn-danger">Delete</button>
                    ' . ($faculty['is_active'] 
                        ? '<button onclick="deactivate(' . $faculty['id'] . ')" class="btn btn-danger">Deactivate</button>'
                        : '<button onclick="activate(' . $faculty['id'] . ')" class="btn btn-success">Activate</button>')
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }
    public function delete($id)
    {
        $facultyModel = new FacultyModel();

        if ($facultyModel->delete($id)) {
            return json_encode(['success' => true, 'message' => 'Faculty deleted successfully']);
        } else {
            return json_encode(['success' => false, 'message' => 'Failed to delete faculty']);
        }
    }
}
