<?php

namespace App\Controllers;

use App\Models\EvaluationModel;
use App\Models\EvaluationAnswerModel;
use App\Models\StudentModel;
use App\Models\AcademicModel;
use App\Models\FacultyModel;
use App\Models\RatingModel;
use App\Models\EvaluationQuestionModel;
use App\Models\CriteriaModel;

class EvaluationAnswerController extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Load the database connection if not autoloaded
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        // Get student_id from the logged-in user's session
        $studentId = session('userId'); // This stores the primary key of the logged-in student

        // Get the academic session with status "1" (active - Start)
        $academic = model('App\Models\AcademicModel')->where('status', 1)->first();

        // Check if there is a valid academic session
        if (!$academic) {
            return redirect()->to('login')->with('error', 'No active academic session found.');
        }

        // Store the academic_id in the session
        session()->set('academic_id', $academic['id']);

        // Fetch all faculty members
        $facultyList = model('App\Models\FacultyModel')->findAll();

        // Load the view with necessary data
        $data = [
            'studentId' => $studentId,
            'academicId' => $academic['id'], // Using session value
            'facultyList' => $facultyList
        ];

        return view('admin/evaluation_form', $data); 
    }


   public function submit()
{
    // Get form data from the request
    $data = $this->request->getPost();

    // Get academic_id and student_id from the session
    $academicId = session('academic_id');
    $studentId = session('userId');

    // Ensure academic_id exists in the session and is valid
    if (!$academicId) {
        return redirect()->back()->with('error', 'Invalid or missing academic session.');
    }

    // Check if the academic session exists in the database
    $academicModel = model('App\Models\AcademicModel');
    $academic = $academicModel->find($academicId);

    if (!$academic) {
        return redirect()->back()->with('error', 'Invalid academic session.');
    }

    // Ensure faculty_id is provided
    if (empty($data['faculty_id'])) {
        return redirect()->back()->with('error', 'Please select a faculty.');
    }

    // Check if the student has already submitted the evaluation for the selected faculty
    $evaluationModel = model('App\Models\EvaluationModel');
    $existingEvaluation = $evaluationModel->where('student_id', $studentId)
                                          ->where('faculty_id', $data['faculty_id'])
                                          ->where('academic_id', $academicId)
                                          ->first();

    if ($existingEvaluation) {
        return redirect()->back()->with('error', 'You have already submitted the evaluation for this instructor this academic semester.');
    }

    // Validate the form data
    if (!$this->validate([
        'comment' => 'required|min_length[10]',
        'faculty_id' => 'required|is_not_unique[faculty_list.id]', // Ensure faculty exists
    ])) {
        // If validation fails, return to the previous page with error messages
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Calculate the final rating 
    $finalRating = $this->calculateFinalRating($data);

    // Save the evaluation data
    $evaluationData = [
        'student_id' => $studentId,  // Use student_id from session
        'faculty_id' => $data['faculty_id'],
        'academic_id' => $academicId,
        'comment' => $data['comment'],
        'final_rating' => $finalRating
    ];

    // Insert evaluation data into the database
    $evaluationModel->insert($evaluationData);
    $evaluationId = $evaluationModel->insertID();  // Get the inserted evaluation ID

    // Save evaluation answers for each question
    $evaluationAnswerModel = model('App\Models\EvaluationAnswerModel');
    foreach ($data as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $evaluationAnswerData = [
                'evaluation_id' => $evaluationId,
                'evaluation_question_id' => str_replace('question_', '', $key),
                'rating_id' => $value
            ];

            $evaluationAnswerModel->insert($evaluationAnswerData);
        }
    }

    // Redirect to the evaluation page with a success message
    return redirect()->to('evaluation/submit')->with('success', 'Evaluation submitted successfully!');
}




    // Function to calculate the final rating based on selected ratings
    private function calculateFinalRating($data)
    {
        $totalRating = 0;
        $questionCount = 0;

        // Loop through the answers and sum the ratings for the questions
        foreach ($data as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $rating = model('App\Models\RatingModel')->find($value);
                if ($rating) {
                    $totalRating += $rating['rate'];
                    $questionCount++;
                }
            }
        }

        // Calculate the average final rating, ensure no division by zero
        $finalRating = $questionCount > 0 ? ($totalRating / $questionCount) : 0;

        return $finalRating;
    }

  public function evaluationResults()
{
    // Get the faculty_id from the session (e.g., after faculty login)
    $facultyId = session()->get('faculty_id'); // or whatever session variable you store it in

    // Ensure that faculty_id exists in the session
    if (!$facultyId) {
        return redirect()->to('/login')->with('error', 'Please log in first.');
    }

    // Get the academic_id from the POST request
    $academicId = $this->request->getPost('academic_id');
    
    // If academic_id is not provided, show the academic options form only
    if (!$academicId) {
        $academicOptions = $this->getAcademicOptions();
        return view('faculty/evaluation_results', ['academicOptions' => $academicOptions]);
    }

    // Fetch evaluations and their answers based on facultyId and academicId
    $evaluations = $this->getEvaluationsWithAnswers($facultyId, $academicId);

    // Fetch available academic options (for the form)
    $academicOptions = $this->getAcademicOptions();

    // Get the selected academic details (school_year, semester)
    $selectedAcademic = null;
    foreach ($academicOptions as $academic) {
        if ($academic['id'] == $academicId) {
            $selectedAcademic = $academic;
            break;
        }
    }

    // Check if any evaluations were found
    if (empty($evaluations)) {
        return view('faculty/evaluation_results', [
            'academicOptions' => $academicOptions,
            'selectedAcademic' => $selectedAcademic,
            'errorMessage' => 'No evaluations found for the selected academic semester.'
        ]);
    }

    // Return the view with evaluations and academic options
    return view('faculty/evaluation_results', [
        'evaluations' => $evaluations,
        'academicOptions' => $academicOptions,
        'selectedAcademic' => $selectedAcademic // Pass the selected academic details
    ]);
}


private function getEvaluationsWithAnswers($facultyId, $academicId)
{
    return $this->db->table('evaluation')
        ->select([
            'evaluation.id AS evaluation_id',
            'evaluation.comment',
            'academic.school_year',
            'academic.semester',
            'evaluation.created_at',
            'evaluation_answer.rating_id',
            'evaluation_answer.evaluation_question_id',
            'evaluation_question.question_text AS question_text',  // Adjusted to ensure proper alias
            'rating.rate AS rating_rate' // Ensure proper column aliases
        ])
        ->join('academic', 'evaluation.academic_id = academic.id')
        ->join('evaluation_answer', 'evaluation.id = evaluation_answer.evaluation_id', 'left')
        ->join('evaluation_question', 'evaluation_answer.evaluation_question_id = evaluation_question.id', 'left')
        ->join('rating', 'evaluation_answer.rating_id = rating.id', 'left')
        ->where('evaluation.faculty_id', $facultyId)
        ->where('evaluation.academic_id', $academicId)
        ->get()
        ->getResultArray(); // Fetch results as an array
}

private function getAcademicOptions()
{
    // Query to get academic year options for the form
    return $this->db->table('academic')
        ->select(['id', 'school_year', 'semester'])
        ->orderBy('school_year', 'DESC')
        ->get()
        ->getResultArray();
}



}
