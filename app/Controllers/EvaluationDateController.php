<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EvaluationDateModel;

class EvaluationDateController extends BaseController
{
    protected $evaluationDateModel;

    public function __construct()
    {
        $this->evaluationDateModel = new EvaluationDateModel();
    }

    // Show all evaluation dates (for the calendar view)
    public function index()
    {
        // Fetch all evaluation dates from the database
        $data['evaluationDates'] = $this->evaluationDateModel->findAll();
        
        // Load the view and pass the evaluation dates
        return view('admin/evaluation_dates', $data);
    }

    // Show the form to create a new evaluation date
    public function create()
    {
        return view('admin/create_evaluation_date');
    }

    // Store a new evaluation date
    public function store()
    {
        // Validation
        if (!$this->validate([
            'open_datetime' => 'required',
            'close_datetime' => 'required',
        ])) {
            return redirect()->to('/evaluation-dates/create')->withInput();
        }

        // Save the evaluation date to the database
        $this->evaluationDateModel->save([
            'open_datetime' => $this->request->getPost('open_datetime'),
            'close_datetime' => $this->request->getPost('close_datetime'),
        ]);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date created successfully');
    }

    // Show the form to edit an evaluation date
    public function edit($id)
    {
        // Fetch the evaluation date by ID
        $data['evaluationDate'] = $this->evaluationDateModel->find($id);

        // Check if the evaluation date exists
        if (empty($data['evaluationDate'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Evaluation Date with ID $id not found");
        }

        return view('admin/edit_evaluation_date', $data);
    }

    // Update an existing evaluation date
    public function update($id)
    {
        // Validation
        if (!$this->validate([
            'open_datetime' => 'required',
            'close_datetime' => 'required',
        ])) {
            return redirect()->to("/evaluation-dates/edit/$id")->withInput();
        }

        // Update the evaluation date in the database
        $this->evaluationDateModel->update($id, [
            'open_datetime' => $this->request->getPost('open_datetime'),
            'close_datetime' => $this->request->getPost('close_datetime'),
        ]);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date updated successfully');
    }

    // Delete an evaluation date
    public function delete($id)
    {
        // Check if the evaluation date exists
        if (!$this->evaluationDateModel->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Evaluation Date with ID $id not found");
        }

        // Delete the evaluation date from the database
        $this->evaluationDateModel->delete($id);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date deleted successfully');
    }

    // Show evaluation dates (for the calendar)
    public function showEvaluationDates()
    {
        // Fetch evaluation dates from the model
        $evaluationDates = $this->evaluationDateModel->findAll();

        // Return them as a JSON response (for calendar events)
        return $this->response->setJSON(array_map(function($date) {
            return [
                'title' => 'Evaluation: ' . $date['id'],
                'start' => $date['open_datetime'],
                'end' => $date['close_datetime'],
                'url' => base_url("/evaluation-dates/view/{$date['id']}"),
                'color' => '#007bff', 
                'textColor' => 'white'
            ];
        }, $evaluationDates));
    }
}
