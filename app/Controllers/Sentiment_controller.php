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
        $csvFile = WRITEPATH . 'data.csv'; // CSV file path
        $pythonScriptPath = APPPATH . 'Controllers/sentiment_analysis.py'; // Python script path

        $data['results'] = $this->analyze_sentiment($csvFile, $pythonScriptPath);

        if (isset($data['results']['error'])) {
            $data['error'] = $data['results']['error'];
            unset($data['results']);
        }

        return view('sentiment_view', $data); // Load the view with results
    }

    private function analyze_sentiment(string $csvFile, string $pythonScriptPath): array
    {
        $results = [];
        $batchSize = 100; // Process texts in batches
        $batch = [];

        // Check if CSV file exists
        if (!file_exists($csvFile)) {
            $this->logger->error("CSV file not found: {$csvFile}");
            return [['text' => 'Error: CSV file not found.', 'sentiment' => 'Error']];
        }

        // Read CSV and process each batch
        if (($handle = fopen($csvFile, "r")) !== false) {
            fgetcsv($handle); // Skip the header row

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
        $command = "C:\\Python312\\python.exe " . escapeshellarg($pythonScriptPath);
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"], // stderr
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            $this->logger->error("Failed to execute Python script: $command");
            return array_fill(0, count($batch), ['text' => '', 'sentiment' => 'Error executing Python script']);
        }

        // Send batch to Python script
        $this->logger->debug("Sending batch to Python: " . json_encode($batch));
        fwrite($pipes[0], json_encode(['texts' => $batch]));
        fclose($pipes[0]);

        // Get the stdout and stderr
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        $this->logger->debug("Python stdout: " . $stdout); // Log the output from Python
        $this->logger->debug("Python stderr: " . $stderr); // Log any errors from Python



        if ($returnCode !== 0) {
            $this->logger->error("Python script error: {$stderr}");
            return array_fill(0, count($batch), ['text' => '', 'sentiment' => "Python script error: {$stderr}"]);
        }

        // Ensure that only valid JSON is processed
        $stdout = trim($stdout); // Remove any extra spaces or newlines

        try {
            $pythonResults = json_decode($stdout, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($pythonResults) && isset($pythonResults['results'])) {
                return $pythonResults['results'];
            } else {
                $this->logger->error("Invalid JSON from Python: {$stdout}");
                return array_fill(0, count($batch), ['text' => '', 'sentiment' => "Invalid JSON from Python"]);
            }
        } catch (JsonException $e) {
            $this->logger->error("JSON error: {$e->getMessage()} - Python output: {$stdout}");
            return array_fill(0, count($batch), ['text' => '', 'sentiment' => "JSON error: {$e->getMessage()}"]);
        }
    }

}
