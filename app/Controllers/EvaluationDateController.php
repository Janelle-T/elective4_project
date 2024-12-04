<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EvaluationDateModel;
date_default_timezone_set('Asia/Manila');

class EvaluationDateController extends BaseController
{
    protected $evaluationDateModel;

    public function __construct()
    {
        $this->evaluationDateModel = new EvaluationDateModel();
        // date_default_timezone_set('Asia/Manila');
    }

    // Show all evaluation dates (for the calendar view)
    public function index()
    {
        // Fetch all evaluation dates from the database
        $data['evaluationDates'] = $this->evaluationDateModel->findAll();
        
        // Load the view and pass the evaluation dates
        return view('admin/evaluation_dates', $data);
    }

    // Show the focreate_rm to create a ew evaluation date
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

    public function showEvaluationDates()
    {
        $evaluationDates = $this->evaluationDateModel->findAll();

        return $this->response->setJSON(array_map(function($date) {
            $utc = new DateTimeZone('UTC');
            $start = new \DateTime($date['open_datetime'], $utc);
            $end = new \DateTime($date['close_datetime'], $utc);


            return [
                'title' => 'Evaluation Period',
                'start' => $start->format('Y-m-d\TH:i:s\Z'),  // ISO8601 with UTC 'Z'
                'end'   => $end->format('Y-m-d\TH:i:s\Z'),    // ISO8601 with UTC 'Z'
                'extendedProps' => [ 
                    'id' => $date['id']
                ]
            ];
        }, $evaluationDates));
    }
}
