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
            // Example data transformation (if needed)
            $siteCodeQuery = $old_db->prepare("SELECT name FROM users WHERE id = ?");
            $siteCodeQuery->bind_param("i", $row['ACL_Master_FK']);
            $siteCodeQuery->execute();
            $siteCodeQuery->store_result();
            $siteCodeQuery->bind_result($user_name);
            $siteCodeQuery->fetch();

            $number = null;
            if (preg_match('/\[(\d+)\]/', $user_name, $matches)) {
                // If a number in square brackets is found
                $number = $matches[1]; // Extract the number
                echo "Number in square brackets: " . $number;
            } else {
                // If no square brackets are found
                echo "No square brackets found in: " . $user_name;
            }
                        
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO clients (client_id,client_number,client_name, is_active,created_at) VALUES (?, ?, ?, ?, ?)");
            $is_active='1';
            $name=$row['ACL_Fisrt_Name'] . ' ' . $row['ACL_Last_Name'];
            $stmt->bind_param("issss", $row['ACL_ID_PK'], $number, $name,$is_active,$row['ACL_User_Add_On']);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
                throw new Exception("Failed to insert data into clients table: " . $row['id'] ."/n". $stmt->error);
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