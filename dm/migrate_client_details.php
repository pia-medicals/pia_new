<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM adm_admin_customer_login");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            // Check if the ACL_Master_FK exists in the users table
            $checkUserQuery = $old_db->prepare("SELECT id FROM users WHERE id = ?");
            $checkUserQuery->bind_param("i", $row['ACL_Master_FK']);
            $checkUserQuery->execute();
            $checkUserQuery->store_result();

            if ($checkUserQuery->num_rows === 0) {
                // If no matching user_id found, echo the row and skip insertion
                echo "Skipping insertion for ACL_Master_FK: " . $row['ACL_Master_FK'] . " - No matching user_id found.\n";
                continue; // Move to the next row
            }

            $siteCodeQuery = $old_db->prepare("SELECT site_code FROM users WHERE id = ?");
            $siteCodeQuery->bind_param("i", $row['ACL_Master_FK']);
            $siteCodeQuery->execute();
            $siteCodeQuery->store_result();
            $siteCodeQuery->bind_result($site_code);
            $siteCodeQuery->fetch();
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO client_details (client_ids,user_ids,site_code,is_active,created_at) VALUES (?, ?, ?,?, ?)");
            $is_active='1';
            $stmt->bind_param("iisss", $row['ACL_ID_PK'],$row['ACL_Master_FK'],$site_code,$is_active,$row['ACL_User_Add_On']);
            

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
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
    echo "Error during migration: " .$row['ACL_Master_FK']. $e->getMessage() . "\n";
} finally {
    // Close connections
    $old_db->close();
    $new_db->close();
}