<?php
namespace App;

class CsvReader {
    public static function read($filePath) {
        $rows = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }
}
