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

    /**
     * Display a list of all evaluation dates.
     */
    public function index()
    {
        $data['evaluationDates'] = $this->evaluationDateModel->findAll();
        return view('admin/evaluation_dates', $data);
    }

    /**
     * Show the form to create a new evaluation date.
     */
    public function create()
    {
        return view('admin/create_evaluation_date');
    }

    /**
     * Store a newly created evaluation date in the database.
     */
    public function store()
    {
        // Validate input
        if (!$this->validate([
            'open_datetime' => 'required|valid_date[Y-m-d H:i:s]',
            'close_datetime' => 'required|valid_date[Y-m-d H:i:s]|check_close_date'
        ])) {
            return redirect()->to('/evaluation-dates/create')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Save the new evaluation date
        $this->evaluationDateModel->save([
            'open_datetime' => $this->request->getPost('open_datetime'),
            'close_datetime' => $this->request->getPost('close_datetime'),
        ]);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date created successfully.');
    }

    /**
     * Show the form to edit an existing evaluation date.
     */
    public function edit($id)
    {
        $data['evaluationDate'] = $this->evaluationDateModel->find($id);

        if (empty($data['evaluationDate'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Evaluation Date with ID $id not found.");
        }

        return view('admin/edit_evaluation_date', $data);
    }

    /**
     * Update an existing evaluation date in the database.
     */
    public function update($id)
    {
        // Validate input
        if (!$this->validate([
            'open_datetime' => 'required|valid_date[Y-m-d H:i:s]',
            'close_datetime' => 'required|valid_date[Y-m-d H:i:s]|check_close_date'
        ])) {
            return redirect()->to("/evaluation-dates/edit/$id")
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Update the evaluation date
        $this->evaluationDateModel->update($id, [
            'open_datetime' => $this->request->getPost('open_datetime'),
            'close_datetime' => $this->request->getPost('close_datetime'),
        ]);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date updated successfully.');
    }

    /**
     * Delete an evaluation date from the database.
     */
    public function delete($id)
    {
        if (!$this->evaluationDateModel->find($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Evaluation Date with ID $id not found.");
        }

        $this->evaluationDateModel->delete($id);

        return redirect()->to('/evaluation-dates')->with('message', 'Evaluation Date deleted successfully.');
    }

    /**
     * Provide evaluation dates as JSON for the calendar view.
     */
    public function showEvaluationDates()
    {
        $evaluationDates = $this->evaluationDateModel->findAll();

        $events = array_map(function ($date) {
            return [
                'title' => 'Evaluation Period',
                'start' => $date['open_datetime'],
                'end' => $date['close_datetime'],
                'url' => base_url("/evaluation-dates/view/{$date['id']}"),
                'color' => '#007bff',
                'textColor' => '#ffffff',
            ];
        }, $evaluationDates);

        return $this->response->setJSON($events);
    }
}
