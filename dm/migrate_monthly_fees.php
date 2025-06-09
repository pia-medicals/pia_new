<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM maintenance");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            $checkClientQuery = $new_db->prepare("SELECT client_account_id FROM client_details WHERE user_ids = ?");
            $checkClientQuery->bind_param("i", $row['customer']);
            $checkClientQuery->execute();
            $checkClientQuery->store_result(); // Buffer the result
            $checkClientQuery->bind_result($client_account_ids);
            $checkClientQuery->fetch();

            // If no matching client_account_ids is found, skip the row
            if (!$client_account_ids) {
                echo "Skipping insertion for customer: " . $row['customer'] . " of rowid " . $row['id'] . " - No matching client_account_ids found.</br>";
                $checkClientQuery->free_result(); // Free the result set
                $checkClientQuery->close(); // Close the statement
                continue;
            }
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO monthly_fees (monthly_fee_id,client_account_ids,monthly_fee_price,monthly_fee_name,is_active,created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $is_active='1';
            $stmt->bind_param("iiiss", $row['id'],$client_account_ids,$row['maintenance_fee_amount'],$row['maintenance_fee_type'],$is_active);

            if ($stmt->execute()) {

                $insertedCount++; // Increment count if insertion is successful
            } else {
                echo "Error".$row['id'];
                throw new Exception("Failed to insert data into clients_details table: " . $row['id'] ."/n". $stmt->error);
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