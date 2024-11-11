<?php

namespace App\Controllers;
use App\Models\StudentModel;
use Config\Services;


class StudentAuthController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }
    public function index()
    {
        return view('student/student_dash'); // Ensure this view exists
    }

    // Student registration form view
    public function sign_up()
    {

        return view('student/student_form');
    }

    // Student registration processing
    public function register()
    {
        $validation = \Config\Services::validation();

        // Define validation rules
        $validation->setRules([
            'student_id' => 'required|is_unique[student_list.student_id]',
            'full_name' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[student_list.email]',
            'password' => 'required|min_length[8]',
            
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Redirect back with errors if validation fails
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare student data for insertion
        $studentModel = new StudentModel();
        $data = [
            'student_id' => $this->request->getPost('student_id'),
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'phoneNumber' => $this->request->getPost('phoneNumber'),
            'email' => $this->request->getPost('email'),
            'passwordHash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'email_verified' => 0, // Not verified initially
            'verification_token' => bin2hex(random_bytes(32)),
            
        ];

        // Insert student into the database
        if ($studentModel->insert($data)) {
            // Send email verification link
            $emailService = \Config\Services::email();
            $emailService->setTo($data['email']);
            $emailService->setSubject('Email Verification');
            $message = 'Please click the link below to verify your email address: ' . base_url('student/verifyEmail/' . $data['verification_token']);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Registration successful! Please verify your email. A verification link has been sent.');
            } else {
                log_message('error', 'Failed to send verification email to: ' . $data['email']);
                session()->setFlashdata('error', 'Failed to send verification email.');
            }

            return redirect()->to('student/sign_up'); // Redirect back to registration page
        } else {
            session()->setFlashdata('error', 'Failed to register student.');
            return redirect()->to('student/sign_up'); // Redirect back to registration page
        }
    }

    // Student email verification
     public function verifyEmail($token)
    {
        $studentModel = new StudentModel();
        $user = $studentModel->where('verification_token', $token)->first();

        if ($user) {
            $studentModel->update($user['id'], ['email_verified' => 1, 'verification_token' => null]);
            session()->setFlashdata('success', 'Email Verified Successfully! You can now log in.');
            return redirect()->to('login');
        }

        session()->setFlashdata('error', 'Email verification failed. Please request a new verification email.');
        return redirect()->to('student/requestVerificationEmail');
    }

    public function student_list()
    {
        return view('admin/student_list'); // Ensure this view exists
    }

    public function getStudentList()
    {
        $studentModel = new StudentModel();
        $studentList = $studentModel->findAll();

        $data = [];
        foreach ($studentList as $index => $student) {

            $data[] = [
                'counter' => $index + 1,
                'student_id' => $student['student_id'],
                'full_name' => $student['full_name'],
                'phone_number' => $student['phoneNumber'],
                'gender' => $student['gender'],
                'email' => $student['email'],
                'actions' => '
                    <button onclick="editStudent(' . $student['id'] . ')" class="btn btn-warning">Edit</button>
                    <button onclick="deleteStudent(' . $student['id'] . ')" class="btn btn-danger">Delete</button>
                    '
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }
    public function delete($id)
    {
        $studentModel = new StudentModel();

        if ($studentModel->delete($id)) {
            return json_encode(['success' => true, 'message' => 'Student deleted successfully']);
        } else {
            return json_encode(['success' => false, 'message' => 'Failed to delete student']);
        }
    }
}
