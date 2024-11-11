<?php

namespace App\Controllers;

use App\Models\AcademicModel;
use App\Models\RatingModel;
use App\Models\CriteriaModel;
use App\Models\EvaluationQuestionModel;
use CodeIgniter\Controller;

class EvaluationController extends Controller
{
    public function __construct()
    {
        // Load models for each form
        $this->academicModel = new AcademicModel();
        $this->ratingModel = new RatingModel();
        $this->criteriaModel = new CriteriaModel();
        $this->evaluationQuestionModel = new EvaluationQuestionModel();
    }

    // Display the Academic Year Form
    public function academicForm()
    {
        $academicModel = new AcademicModel();
        $academicRecords = $academicModel->findAll(); // Fetch all academic records for the table

        return view('admin/academic_form', ['academicRecords' => $academicRecords]);
    }

    // Save a new Academic Year (No status or is_default in the form)
    public function saveAcademic()
    {
        // Get POST data
        $schoolYear = $this->request->getPost('school_year');
        $semester = $this->request->getPost('semester');

        // Validation rules
        $validationRules = [
            'school_year' => 'required|regex_match[/^\d{4}-\d{4}$/]',  // Ensure school year is in the format YYYY-YYYY
            'semester' => 'required|in_list[1,2]',  // Assuming semester 1 or 2
        ];

        // Validate the input data
        if (!$this->validate($validationRules)) {
            // Validation failed, return with errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Initialize the Academic model
        $academicModel = new AcademicModel();

        // By default, set the academic year as "Closed"
        $status = 2; // 2 is Closed by default

        // Prepare the data for saving
        $data = [
            'school_year' => $schoolYear,
            'semester' => $semester,
            'status' => $status,
        ];

        // Save the data to the database
        $academicModel->save($data);

        // Redirect or show success message
        return redirect()->to(base_url('evaluation/academic'))->with('success', 'Academic year saved successfully.');
    }

    // Start an Academic Year (Change status to 1)
    public function startAcademic($id)
    {
        $academicModel = new AcademicModel();

        // Find the academic year by ID
        $academicYear = $academicModel->find($id);

        if ($academicYear) {
            // Update status to 'Start' (1)
            $academicModel->update($id, ['status' => 1]);

            // Set all other records' status to 'Closed' (2)
            $academicModel->where('id !=', $id)->set('status', 2)->update();

            return redirect()->to(base_url('evaluation/academic'))->with('success', 'Academic year started successfully.');
        }

        return redirect()->to(base_url('evaluation/academic'))->with('error', 'Academic year not found.');
    }

    // Close an Academic Year (Change status to 2)
    public function closeAcademic($id)
    {
        $academicModel = new AcademicModel();

        // Find the academic year by ID
        $academicYear = $academicModel->find($id);

        if ($academicYear) {
            // Update status to 'Closed' (2)
            $academicModel->update($id, ['status' => 2]);

            return redirect()->to(base_url('evaluation/academic'))->with('success', 'Academic year closed successfully.');
        }

        return redirect()->to(base_url('evaluation/academic'))->with('error', 'Academic year not found.');
    }


    // Rating Form
    public function ratingForm()
    {
        // Create an instance of the RatingModel
        $ratingModel = new RatingModel();

        // Fetch all rating records from the database
        $ratingRecords = $ratingModel->findAll();  // Assuming 'findAll()' retrieves all records

        // Pass the rating records to the view
        return view('admin/rating_form', [
            'ratingRecords' => $ratingRecords
        ]);
    }

    // Method to handle form submission and save the rating
    public function saveRating()
    {
        $data = $this->request->getPost();

        // Validate the form data
        if ($this->validate([
            'rate' => 'required|numeric|min_length[1]|max_length[5]',
            'descriptive_rating' => 'required|string',
            'qualitative_description' => 'required|string'
        ])) {
            // Create an instance of the RatingModel
            $ratingModel = new RatingModel();

            // Save the rating data to the database
            $ratingModel->save([
                'rate' => $data['rate'],
                'descriptive_rating' => $data['descriptive_rating'],
                'qualitative_description' => $data['qualitative_description'],
            ]);

            // Set success flash message
            session()->setFlashdata('success', 'Rating saved successfully.');

            // Redirect to the rating page
            return redirect()->to('evaluation/rating');
        } else {
            // Set error flash message
            session()->setFlashdata('error', 'Failed to save rating. Please try again.');

            // Redirect to the rating page
            return redirect()->to('evaluation/rating');
        }
    }



    // Rating Form
    public function criteriaForm()
    {
        // Create an instance of the RatingModel
        $criteriaModel = new CriteriaModel();

        // Fetch all rating records from the database
        $criteriaRecords = $criteriaModel->findAll();  // Assuming 'findAll()' retrieves all records

        // Pass the rating records to the view
        return view('admin/criteria_form', [
            'criteriaRecords' => $criteriaRecords
        ]);
    }
       public function saveCriteria()
    {
        $data = $this->request->getPost();

        // Validate the form data
        if ($this->validate([
            'title' => 'required|string'
        ])) {
            // Create an instance of the RatingModel
            $criteriaModel = new CriteriaModel();

            // Save the rating data to the database
            $criteriaModel->save([
                'title' => $data['title'],
            ]);

            // Set success flash message
            session()->setFlashdata('success', 'Criteria data saved successfully.');

            // Redirect to the rating page
            return redirect()->to('evaluation/criteria');
        } else {
            // Set error flash message
            session()->setFlashdata('error', 'Failed to save rating. Please try again.');

            // Redirect to the rating page
            return redirect()->to('evaluation/criteria');
        }
    }

    // Evaluation Question Form
    public function evaluationQuestionForm()
    {
        $evaluationQuestionModel = new EvaluationQuestionModel();

        // Perform join to get criteria title with each question
        $questionRecords = $evaluationQuestionModel
                            ->select('evaluation_question.*, criteria.title AS criteria_title')
                            ->join('criteria', 'criteria.id = evaluation_question.criteria_id')
                            ->findAll();

        $criteria = $this->criteriaModel->findAll();

        return view('admin/evaluation_question_form', [
            'questionRecords' => $questionRecords,
            'criteria' => $criteria
        ]);
    }


    // Save Evaluation Question
    public function saveEvaluationQuestion()
    {
        // Validate form data
        $validationRules = [
            'criteria_id' => 'required',
            'question_text' => 'required'
        ];

        if (!$this->validate($validationRules)) {
            // Redirect back with input and errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'criteria_id' => $this->request->getPost('criteria_id'),
            'question_text' => $this->request->getPost('question_text'),
        ];

        // Save data and set success message
        if ($this->evaluationQuestionModel->save($data)) {
            return redirect()->to(base_url('evaluation/evaluation_question'))
                ->with('success', 'Evaluation Question saved successfully!');
        }

        // Handle save failure
        return redirect()->back()->with('error', 'Failed to save Evaluation Question.');
    }

}
