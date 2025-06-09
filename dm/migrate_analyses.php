<?php
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM analyses");

    if ($oldData === false) {
        throw new Exception("Error fetching data from the old database: " . $old_db->error);
    }

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
          //  print_r($row); // Debugging: print row data
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("
                INSERT INTO analyses 
                (analysis_id, category_ids, analysis_number, analysis_name, analysis_invoicing_description, analysis_price, time_to_analyze, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if ($stmt === false) {
                throw new Exception("Prepare statement failed: " . $new_db->error);
            }

            $is_active = '1';
            $stmt->bind_param(
                "iississs",
                $row['id'],
                $row['category'],
                $row['part_number'],
                $row['name'],
                $row['description'],
                $row['price'],
                $row['minimum_time'],
                $is_active
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert data into analyses table (ID: {$row['id']}): " . $stmt->error);
            }

            $insertedCount++; // Increment count if insertion is successful
        }

        // Commit transaction
        $new_db->commit();

        // Output the count of inserted rows
        echo "Migration successful. Total records inserted: " . $insertedCount . "\n";

    } else {
        echo "No data found in the old database.\n";
    }

} catch (Exception $e) {
    // Rollback transaction in case of error
    if ($new_db->errno) {
        $new_db->rollback();
    }
    echo "Error during migration: " . $e->getMessage() . "\n";
} finally {
    // Close connections
    if (isset($old_db)) {
        $old_db->close();
    }
    if (isset($new_db)) {
        $new_db->close();
    }
}
?>
