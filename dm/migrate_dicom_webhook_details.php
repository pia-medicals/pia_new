<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM webhook");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            // Example data transformation (if needed)
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO dicom_webhook_details(dicom_webhook_id,webhook_temp_ids,accession,mrn ,patient_name,exam_date_time,institution_name, analysis_arrival_time,created_at) VALUES (?, ?, ?, ?, ?, ?,?, NOW(),NOW()");
            $is_active='1';
            $stmt->bind_param("iisssss", $row['id'], $row['id'],$row['accession'], $row['MRN'],$row['name'],$row['exam_date'],$row['institution']);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
                $stmt->free_result(); // Free the result set
                $stmt->close(); // Close the statement
            } else {
                throw new Exception("Failed to insert data into webhook_details table: " . $row['id'] ."/n". $stmt->error);
            }
        }

        // Commit transaction
        $new_db->commit();

        // Output the count of inserted rows
        echo "Migration successful. Total records inserted: " . $insertedCount;

    } else {
        echo "No data found in the old database.";
    }

} catch (Exception $e) {
    // Rollback transaction in case of error
    $new_db->rollback();
    echo "Error during migration: " . $e->getMessage();
} finally {
    // Close connections
    $old_db->close();
    $new_db->close();
}