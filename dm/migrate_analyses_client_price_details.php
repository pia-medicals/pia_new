<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM analyses_rates");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            // Example data transformation (if needed)
            $checkAnalysisQuery = $new_db->prepare("SELECT analysis_name, analysis_id FROM analyses WHERE analysis_id = ?");
            $checkAnalysisQuery->bind_param("i", $row['analysis']);
            $checkAnalysisQuery->execute();
            $checkAnalysisQuery->store_result();            

            if ($checkAnalysisQuery->num_rows === 0) {
                // If no matching user_id found, echo the row and skip insertion
                echo "Skipping insertion for analysis_id: " . $row['analysis'] . " of rowid  " . $row['id'] . " - No matching analysis_id found.</br>";
                $checkAnalysisQuery->free_result(); // Free the result set
                $checkAnalysisQuery->close(); // Close the statement
                continue; // Move to the next row
            }

            // Bind the result to variables
            $checkAnalysisQuery->bind_result($analysis_name, $analysis_id);

            // Fetch the result
            $checkAnalysisQuery->fetch();
            
            // echo "Analysis Name: " . $analysis_name . "<br>";
            // echo "Analysis ID: " . $analysis_id . "<br>";
            

            // Store analysis_name for future use
            $storedAnalysisName = $analysis_name;

            // Step 2: Fetch 'client_account_ids' from 'client_details' based on 'client_id'
            $checkClientQuery = $new_db->prepare("SELECT client_account_id FROM client_details WHERE user_ids = ?");
            $checkClientQuery->bind_param("i", $row['customer']);
            $checkClientQuery->execute();
            $checkClientQuery->store_result(); // Buffer the result
            $checkClientQuery->bind_result($client_account_ids);
            $checkClientQuery->fetch();

            $timeIdQuery = $old_db->prepare("SELECT MAX(time_id) FROM timeline WHERE customer_id = ?");
                $timeIdQuery->bind_param("i", $row['customer']);
                $timeIdQuery->execute();
                $timeIdQuery->store_result(); // Buffer the result
                $timeIdQuery->bind_result($time_id);
                $timeIdQuery->fetch();

            // If no matching client_account_ids is found, skip the row
            if (!$client_account_ids) {
                echo "Skipping insertion for customer: " . $row['customer'] . " of rowid " . $row['id'] . " - No matching client_account_ids found.</br>";
                $checkClientQuery->free_result(); // Free the result set
                $checkClientQuery->close(); // Close the statement
                continue;
            }
            // Prepare and execute insert query in the new database
            $is_active='0';
            if($row['time_id']==$time_id){
                $is_active='1'; 
            }
            $stmt = $new_db->prepare("INSERT INTO analyses_client_price_details (analysis_client_price_id,client_account_ids,analysis_id,analysis_name,analysis_invoicing_description,analysis_client_price,analysis_time,analysis_code,is_active) VALUES (?, ?,?, ?,?, ?, ?,?, ?)");

            
            $stmt->bind_param("iiississs", $row['id'], $client_account_ids,$row['analysis'],$storedAnalysisName, $row['analysis_description'],$row['rate'], $row['min_time'], $row['code'],$is_active);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
                throw new Exception("Failed to insert data into analyses_client_price_details table: " . $row['id'] ."/n". $stmt->error);
            }
        }

        // Free result and close statements
            $checkAnalysisQuery->free_result();
            $checkAnalysisQuery->close();

        // Commit transaction
        $new_db->commit();

        // Output the count of inserted rows
        echo "Migration successful. Total records inserted: " . $insertedCount . "\n";

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