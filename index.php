<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transpose Table</title>
</head>
<body>
    <h1>Transpose Table Data</h1>
    <form method="post" action="">
        <label for="table1">Paste your Table 1 data (include headers in the first row, separate columns with tabs, and rows with new lines):</label><br>
        <textarea name="table1" id="table1" rows="10" cols="80" required></textarea><br><br>
        <button type="submit">Transpose and Save</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table1'])) {
        // Get the input data
        $table1_input = trim($_POST['table1']);

        // Split input into rows
        $rows = explode(PHP_EOL, $table1_input);

        // Extract headers from the first row
        $headers = explode("\t", array_shift($rows));

        // Convert the remaining rows into a structured array
        $table1 = [];
        foreach ($rows as $row) {
            $columns = explode("\t", $row);
            if (count($columns) === count($headers)) { // Ensure valid data format
                $table1[] = $columns; // Store as array of values (headers will be numbered dynamically)
            }
        }

        // Validate input
        if (empty($table1)) {
            echo "<p style='color: red;'>Invalid input. Please provide valid table data.</p>";
            exit;
        }

        // Prepare headers for Table 2
        $table2_headers = [];
        for ($rowIndex = 1; $rowIndex <= count($table1); $rowIndex++) {
            foreach ($headers as $header) {
                $table2_headers[] = "$header $rowIndex"; // Append row number to each header
            }
        }

        // Transpose logic for Table 2
        $table2 = [];
        $numEntries = count($table1);
        $groupSize = $numEntries; // Dynamically include all rows in one output row

        $row = [];
        foreach ($table1 as $rowData) {
            foreach ($rowData as $cell) {
                $row[] = $cell; // Flatten all data into a single row
            }
        }
        $table2[] = $row; // Add the transposed row to Table 2

        // Prepare data for output
        $output = [];
        $output[] = implode("\t", $table2_headers); // Add dynamic headers
        foreach ($table2 as $row) {
            $output[] = implode("\t", $row); // Use tab as a separator
        }

        // Save to a .txt file
        $fileName = 'transposed_table_with_row_headers.txt';
        file_put_contents($fileName, implode(PHP_EOL, $output));

        // Display success message and output preview
        echo "<h2>Table Transposed Successfully</h2>";
        echo "<p>File saved as <strong>$fileName</strong>.</p>";
        echo "<h3>Preview:</h3>";
        echo "<pre>" . htmlspecialchars(implode(PHP_EOL, $output)) . "</pre>";
    }
    ?>
</body>
</html>
