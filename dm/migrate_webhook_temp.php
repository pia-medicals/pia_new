<?php
// Include database configuration
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM webhook_temp");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            // Example data transformation (if needed)
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO webhook_temp (webhook_temp_id, postdata,created_at) VALUES (?, ?,NOW())");
            $stmt->bind_param("is", $row['id'], $row['postdata']);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
                throw new Exception("Failed to insert data into webhook_temp table: " . $row['id'] ."/n". $stmt->error);
            }

            $webhook_row = $old_db->prepare("SELECT * FROM webhook WHERE id = ?");
            $webhook_row->bind_param("i", $row['id']);

            // Execute the query
            $webhook_row->execute();

            // Get the result
            $result = $webhook_row->get_result();

            // Check if a row was returned
            if ($webhook_row = $result->fetch_assoc()) {
                // Begin a transaction in the new database for error handling
                $new_db->begin_transaction();
                
                if(!empty($webhook_row['exam_date'])){
                $converted_date = DateTime::createFromFormat('m/d/Y', $webhook_row['exam_date'])->format('Y-m-d');
                $exam_date_time = $converted_date . ' 00:00:00';
                }
                
                $exam_date_time = '';

                $insertQuery = $new_db->prepare("INSERT INTO dicom_webhook_details(dicom_webhook_id,webhook_temp_ids,accession,mrn ,patient_name,exam_date_time,institution_name, analysis_arrival_time,created_at) VALUES (?, ?, ?, ?, ?, ?,?, NOW(),NOW())");
                $insertQuery->bind_param("iisssss", $row['id'], $webhook_row['id'],$webhook_row['accession'], $webhook_row['MRN'],$webhook_row['name'],$exam_date_time,$webhook_row['institution']);

                // Execute the insert query
                if ($insertQuery->execute()) {
                    // Commit the transaction after successful insertion
                    $new_db->commit();
                    
                } else {
                    throw new Exception("Failed to insert data into new_webhook_table: " . $insertQuery->error);
                }
            } else {
                echo "No data found for id: " . $row['id'] . "\n";
            }

            
        }

        // Commit transaction
        $new_db->commit();

        // Output the count of inserted rows
        echo "Migration successful. Total records inserted: " . $insertedCount."</br>";

    } else {
        echo "No data found in the old database.\n";
    }

} catch (Exception $e) {
    // Rollback transaction in case of error
    $new_db->rollback();
    echo "Error during migration: " . $e->getMessage() . "\n";
} finally {
    // Close connections
    $old_db->close();
    $new_db->close();
}