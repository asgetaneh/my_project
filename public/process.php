<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\CsvReader;
use App\CommissionCalculator;

// Check if a file was uploaded
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $fileName = $_FILES['csv_file']['name'];
    $fileSize = $_FILES['csv_file']['size'];
    $fileType = $_FILES['csv_file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Check if the file is a CSV
    if ($fileExtension === 'csv') {
        // Read the CSV file
        $transactions = CsvReader::read($fileTmpPath);

        // Calculate commissions
        $calculator = new CommissionCalculator($transactions);
        $results = $calculator->process();

        // Output results
        foreach ($results as $result) {
            echo "{$result['commission']}<br>";
        }
    } else {
        echo "Invalid file format. Please upload a CSV file.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
}
