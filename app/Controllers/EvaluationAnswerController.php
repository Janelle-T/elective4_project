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

        // Get the academic session with status "1" (active)
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

        // Validate the form data
        if (!$this->validate([
            'comment' => 'required|min_length[10]',
            'faculty_id' => 'required|is_not_unique[faculty_list.id]', // Ensure faculty exists
            'academic_id' => 'required|is_not_unique[academic.id]', // Ensure academic_id exists
        ])) {
            // If validation fails, return to the previous page with error messages
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get academic_id from session
        $academicId = session('academic_id');
        if (!$academicId) {
            return redirect()->back()->with('error', 'Invalid academic session.');
        }

        // Get student_id from session (primary key of the logged-in student)
        $studentId = session('userId');
        
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
        $evaluationModel = model('App\Models\EvaluationModel');
        $evaluationId = $evaluationModel->insert($evaluationData);

        // Save evaluation answers for each question
        foreach ($data as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                // Prepare the evaluation answer data
                $evaluationAnswerData = [
                    'evaluation_id' => $evaluationId,
                    'evaluation_question_id' => str_replace('question_', '', $key),
                    'rating_id' => $value
                ];

                // Insert the evaluation answer into the database
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
}
