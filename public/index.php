<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Calculator</title>
</head>
<body>
    <h1>Upload CSV File for Commission Calculation</h1>
    <form action="process.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit">Upload and Calculate</button>
    </form>
</body>
</html>
