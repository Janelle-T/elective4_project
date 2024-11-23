<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Log\Logger;
use JsonException;

class Sentiment_controller extends Controller
{
    protected $logger; //for logging errors

    public function __construct()
    {
        $this->logger = \Config\Services::logger(); //get the logger instance
    }

    public function index()
    {
        $csvFile = WRITEPATH . 'data.csv';
        $pythonScriptPath = APPPATH . 'Controllers/sentiment_analysis.py';

        $data['results'] = $this->analyze_sentiment($csvFile, $pythonScriptPath);
        return view('sentiment_view', $data);
    }

    private function analyze_sentiment(string $csvFile, string $pythonScriptPath): array
    {
        $results = [];

        if (!file_exists($csvFile)) {
            $this->logger->error("CSV file not found: {$csvFile}");
            return [['text' => 'Error: CSV file not found.', 'sentiment' => 'Error']];
        }

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $text = $data[0];
                $tempInputFile = tempnam(sys_get_temp_dir(), 'input');
                $tempOutputFile = tempnam(sys_get_temp_dir(), 'output');

                if (!file_put_contents($tempInputFile, $text)) {
                    $this->logger->error("Error writing to temporary input file: {$tempInputFile}");
                    $results[] = ['text' => $text, 'sentiment' => 'Error writing to temp file'];
                    continue;
                }


                $command = "python3 " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($tempInputFile) . " " . escapeshellarg($tempOutputFile) . " 2>&1";
                $this->logger->debug("Command: {$command}"); //Log the command being executed
                $output = shell_exec($command);

                if (file_exists($tempOutputFile)) {
                    $contents = file_get_contents($tempOutputFile);
                    $this->logger->debug("Python output contents: {$contents}");

                    try {
                        $result = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
                        if (isset($result['sentiment'])) {
                            $results[] = ['text' => $text, 'sentiment' => $result['sentiment']];
                        } else if (isset($result['error'])) { //Handle python errors
                            $results[] = ['text' => $text, 'sentiment' => "Python Error: " . $result['error']];
                            $this->logger->error("Python Error: " . $result['error'] . " for text: " . $text);
                        } else {
                            $results[] = ['text' => $text, 'sentiment' => "Error: Invalid JSON from Python. Output: " . $contents];
                            $this->logger->error("Invalid JSON received from Python script. Output: " . $contents);
                        }
                    } catch (JsonException $e) {
                        $results[] = ['text' => $text, 'sentiment' => "JSON Error: " . $e->getMessage() . ". Python Output: " . $contents];
                        $this->logger->error("JSON Error decoding Python output: " . $e->getMessage() . "\nPython Output: " . $contents);
                    }
                    unlink($tempOutputFile);
                } else {
                    $results[] = ['text' => $text, 'sentiment' => 'Error: Python script did not create output file. Output: ' . $output];
                    $this->logger->error("Python script did not create output file. Command: {$command}, Output: {$output}");
                }


                unlink($tempInputFile);
            }
            fclose($handle);
        } else {
            $this->logger->error("Error opening CSV file: {$csvFile}");
            return [['text' => 'Error opening CSV file.', 'sentiment' => 'Error']];
        }
        return $results;
    }
}