<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class FraudDetectionController extends Controller
{
    public function showFraudResults()
    {
        $filePath = 'R:/Desktop/LOGISTIC2/python/fraud_data_from_activity_logs.csv'; // Ensure correct path
        $fraudData = [];

        // Step 1: Fetch data from SQLite and save as CSV
        try {
            Log::info('Fetching fraud data from SQLite database...');
            // Use Python to fetch fraud data
            $pythonScript = "R:/Desktop/LOGISTIC2/python/create_data.py"; // Update with your script path
            $fetchOutput = shell_exec("python $pythonScript");

            // Check for errors in fetching data
            if (strpos(strtolower($fetchOutput), 'error') !== false) {
                return response()->json(['error' => 'Failed to fetch fraud data.']);
            }

            Log::info('Fraud data fetched successfully: ', ['output' => $fetchOutput]);
        } catch (\Exception $e) {
            Log::error('Error during data fetching: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching fraud data.']);
        }

        // Step 2: Train the model
        try {
            // Log the training process
            Log::info('Starting model training...');

            // Call Python training script
            $trainingOutput = shell_exec("python R:/Desktop/LOGISTIC2/python/train_model.py");
            Log::info('Model Training Output:', ['output' => $trainingOutput]);

            // Check for any issues in training
            if (strpos(strtolower($trainingOutput), 'error') !== false) {
                return response()->json(['error' => 'Model training failed. Please check the logs.']);
            }

            Log::info('Model training completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error during model training: ' . $e->getMessage());
            return response()->json(['error' => 'Error during model training.']);
        }

        // Step 3: Read fraud data from the CSV file
        if (file_exists($filePath)) {
            try {
                if (($handle = fopen($filePath, 'r')) !== false) {
                    $headers = array_map('trim', fgetcsv($handle));  // Read headers

                    while (($row = fgetcsv($handle)) !== false) {
                        if (count($row) === count($headers)) {
                            $rowData = array_combine($headers, $row);
                            $fraudData[] = $rowData;
                        }
                    }
                    fclose($handle);
                }
            } catch (\Exception $e) {
                Log::error('Error processing CSV file: ' . $e->getMessage());
                return response()->json(['error' => 'Error processing fraud data CSV file.']);
            }
        } else {
            return response()->json(['error' => 'Fraud data file not found.']);
        }

        // Log fraud data being passed to Python
        Log::info('Data for prediction: ', ['data' => $fraudData]);

        // Step 4: Pass fraud data to the Python script for prediction
        $dataForPrediction = json_encode($fraudData);

        // Create a temporary file to store fraud data
        $tempFilePath = tempnam(sys_get_temp_dir(), 'fraud_data_');
        file_put_contents($tempFilePath, $dataForPrediction);

        try {
            // Log the prediction process
            Log::info('Calling Python prediction script...');

            // Modify the call to Python prediction script to pass the data correctly
            // Ensure the data is passed as a JSON payload
            $pythonOutput = shell_exec("python R:/Desktop/LOGISTIC2/python/predict.py $tempFilePath");

            // Decode Python output (ensure it's in CSV format)
            $predictionResult = json_decode($pythonOutput, true); // We directly print the CSV output, so we should handle it properly here

            // Log the prediction results
            Log::info('Python Prediction Result:', ['result' => $predictionResult]);

            // Return the results to a view
            return view('modules.admin.fraudDetection.index', [
                'message' => 'Fraud detection completed successfully.',
                'fraudData' => $fraudData,
                'predictions' => $predictionResult
            ]);
        } catch (\Exception $e) {
            Log::error('Error during prediction: ' . $e->getMessage());
            return response()->json(['error' => 'Error during prediction.']);
        } finally {
            // Cleanup: Remove temporary file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }

    public function testPython()
{
    // 1. Check if Python exists
    $pythonCheck = shell_exec("where python 2>&1");
    
    // 2. Try a simple command
    $simpleCommand = shell_exec("python -c \"print('Hello from Python')\" 2>&1");
    
    // 3. Try writing a test file
    $testFile = "R:/Desktop/LOGISTIC2/python/test_file_".time().".txt";
    $writeTest = file_put_contents($testFile, "Test at ".date('Y-m-d H:i:s'));
    
    return view('python_test', [
        'python_path' => $pythonCheck,
        'simple_command' => $simpleCommand,
        'file_test' => $writeTest !== false ? "Success! Created $testFile" : "Failed to create file",
        'file_exists' => file_exists($testFile) ? "Yes" : "No"
    ]);
}
}
