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
        // Tokenize all comments (combine comments from all students for this question)
        $comments = $result['tokenized_comments']; // This is a concatenated string of all comments
        $tokenizedComment = $this->tokenizeComment($comments); // Tokenize the entire string
        
        // Analyze sentiment for the concatenated comment
        $sentiment = $this->analyzeSentiment($comments);
        
        // Add tokenized comment and sentiment to the result
        $result['tokenized_comment'] = implode(' ', $tokenizedComment); // Join tokens into a string for display
        $result['sentiment'] = $sentiment;

        // Fetch individual ratings (assuming they are stored in a specific way)
        $result['individual_ratings'] = $this->getIndividualRatings($result['evaluation_question_id'], $facultyId, $academicId);
    }

    // Return the view with summarized results including tokens, sentiment, and individual ratings
    return view('faculty/evaluation_results', [
        'summaryResults' => $summaryResults, // Include tokenized comments, sentiment, and individual ratings
        'academicOptions' => $academicOptions,
        'selectedAcademic' => $selectedAcademic // Pass the selected academic details
    ]);
}

private function getSummarizedEvaluationResults($facultyId, $academicId)
{
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

// Fetch individual ratings for a given question, faculty, and academic semester
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


private function tokenizeComment($comments)
{
    // Expanded list of common stop words
    $stopWords = [
        'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours',
        'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 'herself',
        'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 'this', 'that',
        'these', 'those', 'am', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has',
        'had', 'having', 'do', 'does', 'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or',
        'because', 'as', 'until', 'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between',
        'into', 'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down',
        'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 'there',
        'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 'most', 'other', 'some',
        'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 'than', 'too', 'very', 's', 't', 'can', 'will',
        'just', 'don', 'should', 'now', 'd', 'll', 'm', 'o', 're', 've', 'y', 'ain', 'aren', 'couldn', 'didn', 'doesn',
        'hadn', 'hasn', 'haven', 'isn', 'ma', 'mightn', 'mustn', 'needn', 'shan', 'shouldn', 'wasn', 'weren', 'won', 'wouldn'
    ];

    // Tokenize the comment by splitting into words using regex
    $tokens = preg_split('/[\s,.\'";!?(){}\[\]:]+/', trim($comments), -1, PREG_SPLIT_NO_EMPTY);

    // Convert all tokens to lowercase
    $tokens = array_map('strtolower', $tokens);

    // Remove stop words
    $tokens = array_filter($tokens, function($word) use ($stopWords) {
        return !in_array($word, $stopWords);
    });

    // Re-index the array after filtering
    $tokens = array_values($tokens);

    return $tokens;
}

private function analyzeSentiment($comment)
{
    // Expanded list of positive, negative, and neutral words
    $positiveWords = [
        'good', 'great', 'awesome', 'fantastic', 'amazing', 'excellent', 'positive', 'happy', 
        'joyful', 'love', 'best', 'wonderful', 'satisfied', 'incredible', 'delightful', 'superb', 
        'beautiful', 'pleasant', 'brilliant', 'inspiring', 'remarkable'
    ];

    $negativeWords = [
        'bad', 'terrible', 'awful', 'horrible', 'poor', 'disappointing', 'upset', 'sad', 'angry', 
        'frustrated', 'hate', 'worst', 'unpleasant', 'dislike', 'failed', 'regret', 'miserable', 
        'annoying', 'dreadful', 'upsetting', 'frustrating', 'disgusting', 'tragic', 'distressing', 
        'painful', 'boring', 'strict', 'nothing', 'learn', 'teach', 'unhelpful', 'hard', 'unpleasant'
    ];

    $neutralWords = [
        'okay', 'fine', 'average', 'normal', 'neutral', 'so-so', 'indifferent', 'typical', 'usual', 
        'regular', 'okayish', 'mediocre', 'fair', 'predictable', 'standard'
    ];

    // Define negation words
    $negationWords = ['don\'t', 'not', 'never', 'nothing', 'no', 'none', 'cannot', 'isn\'t', 'wasn\'t'];

    // Convert comment to lowercase for case-insensitive comparison
    $comment = strtolower($comment);

    // Tokenize the comment
    $tokens = $this->tokenizeComment($comment);

    // Initialize counters
    $positiveCount = 0;
    $negativeCount = 0;
    $neutralCount = 0;
    $negationFlag = false; // Flag to indicate if negation is encountered

    // Loop through each token and adjust sentiment counts
    foreach ($tokens as $token) {
        // Check for negation words
        if (in_array($token, $negationWords)) {
            $negationFlag = true; // Set negation flag to true
        }

        // Check if the current token is in positive, negative, or neutral word lists
        if (in_array($token, $positiveWords)) {
            // Reverse sentiment if negation flag is set
            if ($negationFlag) {
                $negativeCount++; // Negated positive word treated as negative
                $negationFlag = false; // Reset negation flag after use
            } else {
                $positiveCount++;
            }
        } elseif (in_array($token, $negativeWords)) {
            // Reverse sentiment if negation flag is set
            if ($negationFlag) {
                $positiveCount++; // Negated negative word treated as positive
                $negationFlag = false; // Reset negation flag after use
            } else {
                $negativeCount++;
            }
        } elseif (in_array($token, $neutralWords)) {
            $neutralCount++;
        }
    }

    // Determine sentiment based on the counts
    if ($positiveCount > $negativeCount && $positiveCount > $neutralCount) {
        return 'Positive';
    } elseif ($negativeCount > $positiveCount && $negativeCount > $neutralCount) {
        return 'Negative';
    } elseif ($neutralCount >= $positiveCount && $neutralCount >= $negativeCount) {
        return 'Neutral';
    } else {
        return 'Neutral'; // Default to neutral if counts are the same or no clear sentiment
    }
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





/**
 * Analyzes sentiment of the comment (improved approach).
 * @param string $comment
 * @return string
 */






}
