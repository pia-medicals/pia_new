<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM subscriptions");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            $checkClientQuery = $old_db->prepare("SELECT id FROM subscription_fees WHERE time_id = ? AND customer=?");
            $checkClientQuery->bind_param("ii", $row['time_id'],$row['customer']);
            $checkClientQuery->execute();
            $checkClientQuery->store_result(); // Buffer the result
            $checkClientQuery->bind_result($subscription_id);
            $checkClientQuery->fetch();

            // If no matching subscription_id is found, skip the row
            if (!$subscription_id) {
                echo "Skipping insertion for subscriptions of rowid " . $row['id'] . " - No matching subscription_id found.</br>";
                $checkClientQuery->free_result(); // Free the result set
                $checkClientQuery->close(); // Close the statement
                continue;
            }

            $timeIdQuery = $old_db->prepare("SELECT MAX(time_id) FROM timeline WHERE customer_id = ?");
                $timeIdQuery->bind_param("i", $row['customer']);
                $timeIdQuery->execute();
                $timeIdQuery->store_result(); // Buffer the result
                $timeIdQuery->bind_result($time_id);
                $timeIdQuery->fetch();

            $checkClientQuery = $new_db->prepare("SELECT analysis_client_price_id FROM analyses_client_price_details WHERE analysis_id = ?");
            $checkClientQuery->bind_param("i", $row['analysis']);
            $checkClientQuery->execute();
            $checkClientQuery->store_result(); // Buffer the result
            $checkClientQuery->bind_result($analysis_client_price_id);
            $checkClientQuery->fetch();
            // Prepare and execute insert query in the new database
                $is_active='0';
            if($row['time_id']==$time_id){
                $is_active='1'; 
            }
            $stmt = $new_db->prepare("INSERT INTO subscription_contents (subscription_content_id,subscription_ids,subscription_volume,analysis_client_price_ids,is_active,created_at) VALUES (?, ?, ?, ?, ?,NOW())");
            $stmt->bind_param("iiiis", $row['id'],$subscription_id,$row['count'],$analysis_client_price_id,$is_active);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
                throw new Exception("Failed to insert data into subscription table: " . $row['id'] ."/n". $stmt->error);
            }
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
    $new_db->rollback();
    echo "Error during migration: " . $e->getMessage() . "\n";
} finally {
    // Close connections
    $old_db->close();
    $new_db->close();
}