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
    set_time_limit(0);

    // Get the faculty_id from the session
    $facultyId = session()->get('faculty_id');

    // Ensure faculty_id exists in the session
    if (!$facultyId) {
        return redirect()->to('/login')->with('error', 'Please log in first.');
    }

    // Get the academic_id from the POST request
    $academicId = $this->request->getPost('academic_id');

    // If academic_id is not provided, show the academic options form only
    if (!$academicId) {
        $academicOptions = $this->getAcademicOptions();
        return view('faculty/evaluation_results', [
            'academicOptions' => $academicOptions
        ]);
    }

    // Fetch summarized evaluations based on facultyId and academicId
    $summaryResults = $this->getSummarizedEvaluationResults($facultyId, $academicId);

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

    // Check if any summary results were found
    if (empty($summaryResults)) {
        return view('faculty/evaluation_results', [
            'academicOptions' => $academicOptions,
            'selectedAcademic' => $selectedAcademic,
            'errorMessage' => 'No evaluations found for the selected academic semester.'
        ]);
    }

    // Process each summary result to include tokens, sentiment, and individual ratings
    foreach ($summaryResults as &$result) {
        // Retrieve all comments related to the current evaluation question
        $comments = $this->getCommentsForQuestion($result['evaluation_question_id'], $facultyId, $academicId);

        // If no comments exist, set default values
        if (empty($comments)) {
            $result['tokenized_comment'] = "No comments available.";
            $result['sentiment'] = "N/A";
            $result['scores'] = [];
        } else {
            // Concatenate all comments for tokenization and sentiment analysis
            $allComments = implode(' ', array_column($comments, 'comment'));

            // Call the Python script for tokenization and sentiment analysis
            $apiResponse = $this->analyzeWithPythonScript($allComments);
            
            // Clean the output (remove unnecessary NLTK log messages)
            $cleanResponse = $this->cleanPythonResponse($apiResponse);

            // Debug log cleaned response
            log_message('debug', 'Cleaned Python response: ' . $cleanResponse);
            
            // Decode the JSON response from the Python script
            $responseData = json_decode($cleanResponse, true);

            // Handle JSON decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Invalid JSON from Python script: ' . json_last_error_msg());
                $result['tokenized_comment'] = "Error in Python response.";
                $result['sentiment'] = "Error: Sentiment not found.";
                $result['scores'] = [];
            } else {
                // Handle missing or invalid data in the Python response
                if (!isset($responseData['tokens']) || !isset($responseData['sentiment'])) {
                    log_message('error', 'Missing data in Python script response.');
                    $result['tokenized_comment'] = "Error: Tokens not found.";
                    $result['sentiment'] = "Error: Sentiment not found.";
                    $result['scores'] = [];
                } else {
                    // Assign tokenized comments, sentiment, and scores
                    $result['tokenized_comment'] = implode(' ', $responseData['tokens']);
                    $result['sentiment'] = $responseData['sentiment'];
                    $result['scores'] = $responseData['scores'];  // Include the sentiment scores (neg, neu, pos, compound)
                }
            }
        }

        // Fetch individual ratings (use a fallback if not available)
        $individualRatings = $this->getIndividualRatings($result['evaluation_question_id'], $facultyId, $academicId);
        $result['individual_ratings'] = $individualRatings ?: [];
    }

    // Return the view with summarized results including tokens, sentiment, scores, and individual ratings
    return view('faculty/evaluation_results', [
        'summaryResults' => $summaryResults,
        'academicOptions' => $academicOptions,
        'selectedAcademic' => $selectedAcademic
    ]);
}


// Function to clean the raw output from Python (strip out NLTK messages)
private function cleanPythonResponse($rawResponse)
{
    // Remove any lines before the JSON response starts (assuming JSON starts after the last line of NLTK messages)
    $jsonStartPos = strpos($rawResponse, '{"tokens"');
    if ($jsonStartPos === false) {
        return '';  // If no valid JSON is found, return an empty string
    }

    return substr($rawResponse, $jsonStartPos); // Extract JSON part
}




private function analyzeWithPythonScript($comments)
{
    $pythonExecutablePath = 'C:/Users/DELL/AppData/Local/Programs/Python/Python312/python.exe';
    $pythonScriptPath = APPPATH . 'Python/sentiment.py';
    $command = escapeshellcmd("$pythonExecutablePath $pythonScriptPath") . ' ' . escapeshellarg($comments);

    // Execute the command and capture output
    $output = shell_exec($command . ' 2>&1');

    // Log the raw output for debugging
    log_message('error', 'Raw Python script output: ' . $output);

    // Check for empty output
    if (empty($output)) {
        log_message('error', 'Empty response from Python script.');
        return json_encode(['error' => 'Empty response from Python script.']);
    }

    // Attempt to decode JSON
    $responseData = json_decode($output, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        log_message('error', 'Invalid JSON from Python script: ' . json_last_error_msg());
        return json_encode(['error' => 'Invalid JSON from Python script.']);
    }

    // Return the decoded response
    return json_encode($responseData);
}


// Add this method to EvaluationAnswerController
private function getCommentsForQuestion($evaluationQuestionId, $facultyId, $academicId)
{
    return $this->db->table('evaluation')
        ->select('comment')
        ->join('evaluation_answer', 'evaluation.id = evaluation_answer.evaluation_id') // Ensure the join is correct
        ->where('evaluation_answer.evaluation_question_id', $evaluationQuestionId)
        ->where('evaluation.faculty_id', $facultyId)
        ->where('evaluation.academic_id', $academicId)
        ->get()
        ->getResultArray(); // Fetch comments for the specific question
}




// Example method to fetch summarized evaluation results from database
private function getSummarizedEvaluationResults($facultyId, $academicId)
{
    // Your database query logic to fetch evaluation results
    return $this->db->table('evaluation')
        ->select([
            'evaluation_answer.evaluation_question_id',
            'evaluation_question.question_text',
            'AVG(rating.rate) AS average_rating', // Calculate average rating per question
            'COUNT(DISTINCT evaluation.id) AS total_evaluations', // Count distinct evaluations
            'GROUP_CONCAT(DISTINCT evaluation.comment ORDER BY evaluation.created_at) AS tokenized_comments' // Concatenate all comments
        ])
        ->join('evaluation_answer', 'evaluation.id = evaluation_answer.evaluation_id', 'left')
        ->join('evaluation_question', 'evaluation_answer.evaluation_question_id = evaluation_question.id', 'left')
        ->join('rating', 'evaluation_answer.rating_id = rating.id', 'left')
        ->where('evaluation.faculty_id', $facultyId)
        ->where('evaluation.academic_id', $academicId)
        ->groupBy('evaluation_answer.evaluation_question_id') // Group by question
        ->get()
        ->getResultArray(); // Fetch results as an array
}

// Example method to fetch individual ratings for a given question, faculty, and academic semester
private function getIndividualRatings($evaluationQuestionId, $facultyId, $academicId)
{
    return $this->db->table('evaluation')
        ->select('rating.rate')
        ->join('evaluation_answer', 'evaluation.id = evaluation_answer.evaluation_id')
        ->join('rating', 'evaluation_answer.rating_id = rating.id')
        ->where('evaluation.faculty_id', $facultyId)
        ->where('evaluation.academic_id', $academicId)
        ->where('evaluation_answer.evaluation_question_id', $evaluationQuestionId)
        ->get()
        ->getResultArray(); // Fetch all individual ratings
}

private function getAcademicOptions()
    {
        return $this->db->table('academic')
            ->select(['id', 'school_year', 'semester'])
            ->orderBy('school_year', 'DESC')
            ->get()
            ->getResultArray();
    }




/**
 * Analyzes sentiment of the comment (improved approach).
 * @param string $comment
 * @return string
 */






}
