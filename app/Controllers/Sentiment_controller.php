<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Log\Logger;
use JsonException;

class Sentiment_controller extends Controller
{
    protected $logger;

    public function __construct()
    {
        $this->logger = \Config\Services::logger();
    }

    public function index()
    {
        $csvFile = WRITEPATH . 'data.csv';
        $pythonScriptPath = APPPATH . 'Controllers/sentiment_analysis.py';

        $data['results'] = $this->analyze_sentiment($csvFile, $pythonScriptPath);

        if (isset($data['results']['error'])) {
            $data['error'] = $data['results']['error'];
            unset($data['results']);
        }

        return view('sentiment_view', $data);
    }

    private function analyze_sentiment(string $csvFile, string $pythonScriptPath): array
    {
        $results = [];
        $batchSize = 100;
        $batch = [];

        if (!file_exists($csvFile)) {
            $this->logger->error("CSV file not found: {$csvFile}");
            return [['text' => 'Error: CSV file not found.', 'sentiment' => 'Error']];
        }

        if (($handle = fopen($csvFile, "r")) !== false) {
            fgetcsv($handle); 

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $batch[] = $data[0];
                if (count($batch) >= $batchSize) {
                    $results = array_merge($results, $this->processBatch($batch, $pythonScriptPath));
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $results = array_merge($results, $this->processBatch($batch, $pythonScriptPath));
            }

            fclose($handle);
        } else {
            $this->logger->error("Error opening CSV file: {$csvFile}");
            return [['text' => 'Error opening CSV file.', 'sentiment' => 'Error']];
        }

        return $results;
    }


    private function processBatch(array $batch, string $pythonScriptPath): array
    {
        $tempInputFile = tempnam(sys_get_temp_dir(), 'input_');
        $tempOutputFile = tempnam(sys_get_temp_dir(), 'output_');

        $this->logger->debug("Sending batch to Python: " . json_encode($batch));
        file_put_contents($tempInputFile, json_encode(['texts' => $batch]));

        $command = "C:\\Python312\\python.exe " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($tempInputFile) . " " . escapeshellarg($tempOutputFile);

        $returnCode = 0;
        if (function_exists('exec')) {
            exec($command, $output, $returnCode);
        } else {
            $this->logger->error("The 'exec' function is not available on this server.");
            $returnCode = -1; 
        }



        if ($returnCode !== 0) {
            $errorMessage =  "Python script error: " . implode("\n", $output);
            $this->logger->error($errorMessage);
            return array_fill(0, count($batch), ['text' => '', 'sentiment' => $errorMessage]);

        }

        $stdout = file_get_contents($tempOutputFile);


        try {
            $pythonResults = json_decode($stdout, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($pythonResults) && isset($pythonResults['results'])) {
                $results = $pythonResults['results']; // Assign to $results
            } else {
                $this->logger->error("Invalid JSON from Python: {$stdout}");
                return array_fill(0, count($batch), ['text' => '', 'sentiment' => "Invalid JSON from Python"]);
            }
        } catch (JsonException $e) {
            $this->logger->error("JSON error: {$e->getMessage()} - Python output: {$stdout}");
            return array_fill(0, count($batch), ['text' => '', 'sentiment' => "JSON error: {$e->getMessage()}"]);
        }

        unlink($tempInputFile);
        unlink($tempOutputFile);

        return $results;  
    }
}