<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\CollegeModel;
use App\Models\DepartmentModel;
use CodeIgniter\Controller;
use Config\Services;

class AdminAuthController extends BaseController
{
    protected $collegeModel;
    protected $departmentModel;

    public function __construct()
    {
        helper(['form']);
        $this->collegeModel = new CollegeModel(); // Initialize CollegeModel
        $this->departmentModel = new DepartmentModel(); // Initialize DepartmentModel
    }

    public function index()
    {
        return view('admin/admin_dash'); // Ensure this view exists
    }

    public function sign_up(): string
    {
        return view('admin/sign_up');
    }

    public function register()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'full_name' => 'required|min_length[2]',
            'email' => [
                'rules' => 'required|valid_email|is_unique[admin.email]',
                'errors' => [
                    'is_unique' => 'This email is already registered. Please use another email.'
                ]
            ],
            'password' => 'required|min_length[8]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $AdminModel = new AdminModel();
        $email = $this->request->getPost('email');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $email,
            'passwordHash' => $password,
            'verification_token' => bin2hex(random_bytes(32)),
            'email_verified' => 0 // Initialize email verification status
        ];

        // Insert user data into the database
        if ($AdminModel->insert($data)) {
            $userId = $AdminModel->getInsertID();

            // Send verification email
            $emailService = \Config\Services::email();
            $emailService->setTo($data['email']);
            $emailService->setSubject('Email Verification');
            $message = 'Please click on the link below to verify your email address: ' . base_url('admin/verifyEmail/' . $data['verification_token']);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Please verify your email. A verification link has been sent to your email address.');
            } else {
                log_message('error', 'Failed to send verification email to: ' . $data['email']);
                session()->setFlashdata('error', 'Failed to send verification email.');
            }

            return redirect()->back();
        } else {
            // Handle insert failure
            session()->setFlashdata('error', 'Failed to register user.');
            return redirect()->back()->with('errors', $AdminModel->errors());
        }
    }

    public function verifyEmail($token)
    {
        $AdminModel = new AdminModel();
        $user = $AdminModel->where('verification_token', $token)->first();

        if ($user) {
            $AdminModel->update($user['id'], ['email_verified' => 1, 'verification_token' => null]);
            session()->setFlashdata('success', 'Email Verified Successfully! You can now log in.');
            return redirect()->to('/admin');
        }

        session()->setFlashdata('error', 'Email verification failed. Please request a new verification email.');
        return redirect()->to('/admin/requestVerificationEmail');
    }

    public function collegeForm()
    {
        return view('admin/college');
    }

    public function deptForm()
    {
        $data['colleges'] = $this->collegeModel->findAll(); // Get all colleges from the database
        return view('admin/department', $data);
    }

    public function saveCollege()
    {
        $collegeName = $this->request->getPost('college_name');

        // Check if college already exists
        $existingCollege = $this->collegeModel->where('college_name', $collegeName)->first();

        if ($existingCollege) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'College name already exists.'
            ]);
        }

        // Save the new college if no existing college found
        $this->collegeModel->save([
            'college_name' => $collegeName
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'College added successfully.'
        ]);
    }

    public function updateCollege($id)
    {
        $collegeName = $this->request->getPost('college_name');

        // Check if college name already exists (excluding the current college)
        $existingCollege = $this->collegeModel->where('college_name', $collegeName)
                                              ->where('college_id !=', $id) // Exclude the current college
                                              ->first();

        if ($existingCollege) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'College name already exists.'
            ]);
        }

        // Update the existing college
        $this->collegeModel->update($id, [
            'college_name' => $collegeName
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'College updated successfully.'
        ]);
    }

    public function getColleges()
    {
        $colleges = $this->collegeModel->findAll(); // Retrieve all colleges

        if ($colleges) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $colleges
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No colleges found.'
            ]);
        }
    }

    public function deleteCollege($id)
    {
        // Find the college to delete
        $college = $this->collegeModel->find($id);

        if (!$college) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'College not found'
            ]);
        }

        // Delete the college
        $this->collegeModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'College deleted successfully'
        ]);
    }

    public function saveDepartment()
    {
        // Get form input
        $college_id = $this->request->getPost('college');
        $department_name = $this->request->getPost('department_name');

        // Validate input
        if (empty($college_id) || empty($department_name)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }

        // Check if department already exists in the selected college
        $existingDepartment = $this->departmentModel
            ->where('college_id', $college_id)
            ->where('department_name', $department_name)
            ->first();

        if ($existingDepartment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This department already exists in the selected college.'
            ]);
        }

        // Save the department
        $data = [
            'college_id' => $college_id,
            'department_name' => $department_name
        ];

        if ($this->departmentModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add department.'
            ]);
        }
    }

    public function getDepartments()
    {
        // Fetch all departments along with college name using JOIN
        $departments = $this->departmentModel
                            ->join('college', 'college.id = department.college_id')
                            ->findAll();

        if ($departments) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $departments
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No departments found'
            ]);
        }
    }

    public function admin_list()
    {
        return view('admin/admin_list'); // Ensure this view exists
    }

   public function getAdminList()
    {
        $AdminModel = new AdminModel();

        // Assuming this fetches the list of admins from the model or database.
        $adminList = $AdminModel->findAll(); 

        $data = [];
        foreach ($adminList as $index => $admin) {

            // Build the data array to send back
            $data[] = [
                'counter' => $index + 1,
                'full_name' => $admin['full_name'],
                'email' => $admin['email'],
                'actions' => '
                    <button onclick="editAdmin(' . $admin['id'] . ')" class="btn btn-warning">Edit</button>
                    <button onclick="deleteAdmin(' . $admin['id'] . ')" class="btn btn-danger">Delete</button>'
            ];
        }

        // Return data in JSON format
        return $this->response->setJSON(['data' => $data]);
    }

}
