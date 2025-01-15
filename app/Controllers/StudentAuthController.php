<?php

namespace App\Controllers;
use App\Models\StudentModel;
use App\Models\EvaluationDateModel; // Added this line
use Config\Services;


class StudentAuthController extends BaseController
{

    public function __construct()
    {
        helper(['form']);
    }
    public function showEvaluationDates() {
        $evaluationDateModel = new EvaluationDateModel();
        $evaluationDates = $evaluationDateModel->getEvaluationDates();

        $formattedEvents = [];
        foreach ($evaluationDates as $date) {
            $formattedEvents[] = [
                'title' => 'Evaluation Open',
                'start' => $date['open_datetime'], //Already in correct format
                'end' => $date['close_datetime']  //Already in correct format
            ];
        }

        return $this->response->setJSON($formattedEvents);
    }

    public function index()
    {
        $evaluationDateModel = new EvaluationDateModel();
        $data['isEvaluationOpen'] = $evaluationDateModel->isEvaluationOpen();
        return view('student/student_dash', $data);
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

    public function dashboard()
    {
        $evaluationDateModel = new EvaluationDateModel();
        $data['evaluationDates'] = $evaluationDateModel->getEvaluationDates();
        $data['isEvaluationOpen'] = $evaluationDateModel->isEvaluationOpen(); // Crucial: set this variable
        return view('student/student_dash', $data);
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

public function import()
{
    $file = $this->request->getFile('importFile');

    // Validate uploaded file
    if (!$file->isValid() || !in_array($file->getClientExtension(), ['xlsx', 'xls'])) {
        return redirect()->to('student/list')->with('error', 'Invalid file upload. Please upload a valid Excel file.');
    }

    $filePath = $file->getTempName();
    $successCount = 0;
    $failureCount = 0;

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $studentsData = [];

        foreach ($sheet->getRowIterator(2) as $row) {
            $student_id = $sheet->getCell('A' . $row->getRowIndex())->getValue();
            $full_name = $sheet->getCell('B' . $row->getRowIndex())->getValue();
            $email = $sheet->getCell('C' . $row->getRowIndex())->getValue();

            // Skip rows with missing required fields
            if (empty($student_id) || empty($full_name) || empty($email)) {
                $failureCount++;
                continue;
            }

            $generatedPassword = bin2hex(random_bytes(8));
            $hashedPassword = password_hash($generatedPassword, PASSWORD_BCRYPT);
            $verificationToken = bin2hex(random_bytes(32));

            $studentsData[] = [
                'student_id'        => $student_id,
                'full_name'         => $full_name,
                'email'             => $email,
                'phoneNumber'       => null,
                'gender'            => null,
                'passwordHash'      => $hashedPassword,
                'reset_token'       => null,
                'token_created_at'  => null,
                'email_verified'    => 0,
                'verification_token'=> $verificationToken
            ];

            // Send email
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setFrom('noreply@gmail.com', 'USTP-Faculty Evaluation');
            $emailService->setSubject('Welcome Trailblazers');

            // Use HTML for the message to make the link clickable
            $message = "
                <p>Hello, good day! Below are your login details:</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Password:</strong> {$generatedPassword}</p>
                <p>Please click on the link below to verify your email address:</p>
                <p><a href='" . base_url('student/verifyEmail/' . $verificationToken) . "'>Verify Email</a></p>
            ";
            $emailService->setMessage($message);
            $emailService->setMailType('html'); // Ensures the email is sent in HTML format

            if (!$emailService->send()) {
                log_message('error', 'Email sending failed: ' . $emailService->printDebugger());
                $failureCount++;
            } else {
                $successCount++;
            }


        }

        // Insert data into the database
        if (!empty($studentsData)) {
            $model = new \App\Models\StudentModel();
            $model->insertBatch($studentsData);
        }

        // Prepare success and failure messages
        $successMessage = "{$successCount} students imported successfully.";
        if ($failureCount > 0) {
            $errorMessage = "{$failureCount} students could not be imported due to missing or invalid data or email issues.";
            return redirect()->to(base_url('student/list'))->with('success', $successMessage)->with('error', $errorMessage);
        }

        return redirect()->to(base_url('student/list'))->with('success', $successMessage);

    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        return redirect()->to('student/list')->with('error', 'Error processing the file: ' . $e->getMessage());
    } catch (\Exception $e) {
        return redirect()->to('student/list')->with('error', 'An unexpected error occurred: ' . $e->getMessage());
    }
}


}
