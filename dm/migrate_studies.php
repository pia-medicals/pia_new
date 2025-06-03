<?php

// Include database configuration
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$statusArray = [
    0 => null,
    1 => 'Completed',
    2 => 'Under review',
    3 => 'In progress',
    4 => 'Cancelled',
    5 => 'On hold',
    6 => 'CancelledAcc',
    7 => 'CancelledCust',
];

try {
    // Query to fetch data from the old database
    $qry = "SELECT t1.*, t2.accession, t2.mrn, t2.assignee, t2.patient_name, t2.site, t2.tat FROM worksheets t1 INNER JOIN clario t2 ON (t1.clario_id=t2.id)";
    $oldData = $old_db->query($qry);

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {

            $checkClientAccountQuery = $new_db->prepare("SELECT client_account_id FROM client_details WHERE user_ids = ?");
            $checkClientAccountQuery->bind_param("i", $row['customer_id']);
            $checkClientAccountQuery->execute();
            $checkClientAccountQuery->store_result();
            if ($checkClientAccountQuery->num_rows === 0) {
                // If no matching user_id found, echo the row and skip insertion
                echo "Skipping insertion for studies: " . $row['customer_id'] . " of rowid  " . $row['id'] . " - No matching customer_id found.</br>";
                $checkClientAccountQuery->free_result(); // Free the result set
                $checkClientAccountQuery->close(); // Close the statement
                continue; // Move to the next row
            }
            // Bind the result to variables
            $checkClientAccountQuery->bind_result($client_account_id);
            // Fetch the result
            $checkClientAccountQuery->fetch();

            $checkWebhookQuery = $new_db->prepare("SELECT dicom_webhook_id FROM dicom_webhook_details WHERE accession = ? AND mrn = ?");
            $checkWebhookQuery->bind_param("ss", $row['accession'], $row['mrn']);
            $checkWebhookQuery->execute();
            $checkWebhookQuery->store_result();
            // Bind the result to variables
            $checkWebhookQuery->bind_result($dicom_webhook_id);
            // Fetch the result
            $checkWebhookQuery->fetch();

            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO studies (client_account_ids, accession, mrn, patient_name, client_site_name, analyst_id, second_analyst_id, comment, second_comment, second_check_date, dicom_webhook_ids, expected_time, completed_time, analyst_hours, status_ids, actual_tat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $row['accession'] = $row['accession'] ?? null;
            $row['mrn'] = $row['mrn'] ?? null;
            $row['patient_name'] = $row['patient_name'] ?? null;
            $row['site'] = $row['site'] ?? null;
            $row['analyst'] = !empty($row['analyst']) ? $row['analyst'] : null;
            $row['review_user_id'] = !empty($row['review_user_id']) ? $row['review_user_id'] : null;
            $row['other_notes'] = $row['other_notes'] ?? null;
            $row['second_comment'] = $row['second_comment'] ?? null;
            $row['second_check_date'] = $row['second_check_date'] ?? null;
            $dicom_webhook_id = $dicom_webhook_id ?? null;
            $row['expected_time'] = $row['expected_time'] ?? null;
            $row['completed_time'] = $row['completed_time'] ?? null;
            $row['analyst_hours'] = isset($row['analyst_hours']) ? number_format($row['analyst_hours'], 2) : null;
            $status = $statusArray[$row['status']] ?? null;
            $row['tat'] = $row['tat'] ?? null;

            $stmt->bind_param("issssiisssidsdii", $client_account_id, $row['accession'], $row['mrn'], $row['patient_name'], $row['site'], $row['analyst'], $row['review_user_id'], $row['other_notes'], $row['second_comment'], $row['second_check_date'], $dicom_webhook_id, $row['expected_time'], $row['completed_time'], $row['analyst_hours'], $status, $row['tat']);

            if ($stmt->execute()) {
                $new_db->commit();
                $insertedCount++; // Increment count if insertion is successful
            } else {
                echo "Skipping inserting data into studies table: " . $row['id'] . "/n" . $stmt->error;
                continue;
                //throw new Exception("Failed to insert data into studies table: " . $row['id'] . "/n" . $stmt->error);
            }
        }
        
        $checkClientAccountQuery->free_result();
        $checkClientAccountQuery->close();
        
        $checkWebhookQuery->free_result(); 
        $checkWebhookQuery->close();
        
        // Commit transaction
        $new_db->commit();
        // Output the count of inserted rows
        echo "Migration successful. Total records inserted: " . $insertedCount . "</br>";
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