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
    $evaluationId = $evaluationModel->insert($evaluationData);

    // Save evaluation answers for each question
    foreach ($data as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $evaluationAnswerData = [
                'evaluation_id' => $evaluationId,
                'evaluation_question_id' => str_replace('question_', '', $key),
                'rating_id' => $value
            ];

            model('App\Models\EvaluationAnswerModel')->insert($evaluationAnswerData);
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

    public function evaluationResults($facultyId, $academicId)
    {
        // Instantiate the EvaluationModel
        $evaluationModel = new EvaluationModel();

        // Fetch evaluations for the given faculty and academic semester
        $evaluations = $evaluationModel->getFacultyEvaluationsBySemester($facultyId, $academicId);

        // Pass the evaluations data to the view
        return view('faculty/evaluation_results', ['evaluations' => $evaluations]);
    }


    public function getFacultyEvaluationsBySemester($facultyId, $academicId)
    {
        // Load the database library
        $db = \Config\Database::connect();

        // Define the query to get evaluations for the selected faculty and academic semester
        $query = $db->table('evaluation')
                    ->select('evaluation.id, evaluation.student_id, evaluation.faculty_id, evaluation.academic_id, evaluation.comment, evaluation.created_at, evaluation.updated_at')
                    ->join('academic', 'evaluation.academic_id = academic.id')
                    ->where('evaluation.faculty_id', $facultyId)
                    ->where('evaluation.academic_id', $academicId)
                    ->get();

        // Return the result
        return $query->getResult();
    }


    public function fetchResultsByFacultyWithSentiment($academicId = null)
    {
        // Load models
        $facultyModel = model('App\Models\FacultyModel');
        $evaluationModel = model('App\Models\EvaluationModel');
        $evaluationAnswerModel = model('App\Models\EvaluationAnswerModel');
        $ratingModel = model('App\Models\RatingModel');
        $academicModel = model('App\Models\AcademicModel');
        helper('text');

        // Fetch logged-in faculty ID (assumes the faculty is logged in and their ID is stored in session)
        $loggedInFacultyId = session()->get('faculty_id'); // Modify as needed to get the logged-in faculty ID

        // Fetch academic years for the dropdown
        $academics = $academicModel->findAll();

        // Format the academic year and semester as "school_year - semester"
        foreach ($academics as &$academic) {
            $semesterText = $academic['semester'] == 1 ? 'Semester 1' : 'Semester 2';
            $academic['formatted_academic'] = $academic['school_year'] . ' - ' . $semesterText;
        }

        // If no academicId is passed, default to the first option
        if ($academicId === null) {
            $academicId = $academics[0]['id'];
        }

        // Fetch evaluations based on the selected academic year and the logged-in faculty ID
        $results = [];
        $facultyList = $facultyModel->findAll();

        foreach ($facultyList as $faculty) {
            // Only fetch evaluations for the logged-in faculty
            if ($faculty['id'] != $loggedInFacultyId) continue;

            // Fetch evaluations for the selected academic year (both semesters)
            $evaluations = $evaluationModel
                ->where('faculty_id', $faculty['id'])
                ->where('academic_id', $academicId)
                ->findAll();

            if (empty($evaluations)) continue;

            $facultyData = [
                'faculty_id' => $faculty['id'],
                'faculty_name' => $faculty['full_name'],
                'average_rating' => 0,
                'comments' => [],
                'sentiments' => ['positive' => 0, 'neutral' => 0, 'negative' => 0],
                'ratings_per_criteria' => [] // Store ratings per criteria for visualization
            ];

            $totalRatings = 0;
            $ratingCount = 0;

            foreach ($evaluations as $evaluation) {
                $facultyData['comments'][] = $evaluation['comment'];

                // Perform tokenized sentiment analysis on the comment
                $facultyData['sentiments'][$this->analyzeSentimentWithTokens($evaluation['comment'])]++;

                // Fetch all answers for this evaluation
                $answers = $evaluationAnswerModel->where('evaluation_id', $evaluation['id'])->findAll();

                foreach ($answers as $answer) {
                    $rating = $ratingModel->find($answer['rating_id']);
                    if ($rating) {
                        $totalRatings += $rating['rate'];
                        $ratingCount++;

                        // Store ratings per criteria for visualization
                        if (!isset($facultyData['ratings_per_criteria'][$answer['evaluation_question_id']])) {
                            $facultyData['ratings_per_criteria'][$answer['evaluation_question_id']] = 0;
                        }
                        $facultyData['ratings_per_criteria'][$answer['evaluation_question_id']] += $rating['rate'];
                    }
                }
            }

            $facultyData['average_rating'] = $ratingCount > 0 ? ($totalRatings / $ratingCount) : 0;
            $results[] = $facultyData;
        }

        // Prepare chart data for visualization
        $chartData = [
            'labels' => array_keys($results[0]['ratings_per_criteria'] ?? []), // List of criteria
            'ratings' => array_values($results[0]['ratings_per_criteria'] ?? []) // Ratings per criteria
        ];

        // If the request is an AJAX request, return the results as JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'results' => view('faculty/partials/evaluation_results', ['results' => $results, 'loggedInFacultyId' => $loggedInFacultyId, 'chartData' => $chartData]),
                'academics' => $academics,
                'selectedAcademicId' => $academicId
            ]);
        }

        // If not an AJAX request, load the view with the data
        return view('faculty/evaluation_results', [
            'results' => $results,
            'academics' => $academics,
            'selectedAcademicId' => $academicId,
            'loggedInFacultyId' => $loggedInFacultyId,
            'chartData' => $chartData // Pass chart data to the view
        ]);
    }

    // Sentiment analysis function
    private function analyzeSentimentWithTokens($comment)
    {
        // Tokenize the comment into words
        $tokens = preg_split('/[\s,.!?;:()]+/', strtolower($comment), -1, PREG_SPLIT_NO_EMPTY);

        // Define positive and negative words (expand as necessary)
        $positiveWords = ['good', 'excellent', 'great', 'amazing', 'positive', 'happy', 'wonderful', 'outstanding'];
        $negativeWords = ['bad', 'poor', 'terrible', 'negative', 'sad', 'horrible', 'awful', 'disappointing'];

        // Count positive and negative words in the tokens
        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($tokens as $token) {
            if (in_array($token, $positiveWords)) {
                $positiveCount++;
            }
            if (in_array($token, $negativeWords)) {
                $negativeCount++;
            }
        }

        // Determine sentiment based on counts
        if ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount) {
            return 'negative';
        }

        return 'neutral';
    }


}
