<?php
// Include database configuration
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


try {
    // Query to fetch data from the old database
    $qry = "SELECT t0.*, t2.accession, t2.mrn, t2.assignee, t1.review_user_id, t1.analyst FROM worksheet_details t0 INNER JOIN worksheets t1 ON (t0.worksheet_id = t1.id) INNER JOIN clario t2 ON (t1.clario_id=t2.id)";
    $oldData = $old_db->query($qry);

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {

            $checkClientAccountQuery = $new_db->prepare("SELECT t1.client_account_id, t2.analysis_client_price_id FROM client_details t1 INNER JOIN analyses_client_price_details t2 ON (t1.client_account_id = t2.client_account_ids) WHERE t1.user_ids = ? AND t2.analysis_id = ?");
            $checkClientAccountQuery->bind_param("ii", $row['customer_id'], $row['ans_id']);
            $checkClientAccountQuery->execute();
            $checkClientAccountQuery->store_result();
            if ($checkClientAccountQuery->num_rows === 0) {
                // If no matching user_id found, echo the row and skip insertion
                echo "Skipping insertion for analysis_performed: " . $row['customer_id'] . " of rowid  " . $row['id'] . " - No matching analyses_id or customer_id found.</br>";
                $checkClientAccountQuery->free_result(); // Free the result set
                $checkClientAccountQuery->close(); // Close the statement
                continue; // Move to the next row
            }
            // Bind the result to variables
            $checkClientAccountQuery->bind_result($client_account_id, $analysis_client_price_id);
            // Fetch the result
            $checkClientAccountQuery->fetch();
            
            
            $checkStudiesQuery = $new_db->prepare("SELECT studies_id FROM studies WHERE client_account_ids = ? AND accession = ? AND mrn = ?");
            $checkStudiesQuery->bind_param("iss", $client_account_id, $row['accession'], $row['mrn']);
            $checkStudiesQuery->execute();
            $checkStudiesQuery->store_result();
            if ($checkStudiesQuery->num_rows === 0) {
                // If no matching user_id found, echo the row and skip insertion
                echo "Skipping insertion for studies: " . $client_account_id . " of rowid  " . $row['id'] . " - ".$row['accession']." ---- ".$row['mrn']."</br>";
                $checkStudiesQuery->free_result(); // Free the result set
                $checkStudiesQuery->close(); // Close the statement
                continue; // Move to the next row
            }
            // Bind the result to variables
            $checkStudiesQuery->bind_result($studies_id);
            // Fetch the result
            $checkStudiesQuery->fetch();
                        
           
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO analyses_performed (studies_ids, analysis_client_price_ids, quantity, analysis_client_price) VALUES (?, ?, ?, ?)");

            $row['qty'] = $row['qty'] ?? null;
            $row['rate'] = $row['rate'] ?? null;

            $stmt->bind_param("iiii", $studies_id, $analysis_client_price_id, $row['qty'], $row['rate']);

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
        
        $checkStudiesQuery->free_result();
        $checkStudiesQuery->close();
        
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