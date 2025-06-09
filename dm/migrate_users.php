<?php
// Include database configuration
include 'db_config.php';

try {
    // Query to fetch data from the old database
    $oldData = $old_db->query("SELECT * FROM users");

    if ($oldData->num_rows > 0) {
        // Begin a transaction in the new database for error handling
        $new_db->begin_transaction();

        // Insert counter
        $insertedCount = 0;

        while ($row = $oldData->fetch_assoc()) {
            // Example data transformation (if needed)
            
            // Prepare and execute insert query in the new database
            $stmt = $new_db->prepare("INSERT INTO users (user_id,user_name,email,password,last_reset,user_type_ids, is_active,created_at) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
            $is_active='1';
            $stmt->bind_param("issssiss", $row['id'], $row['name'],$row['email'], $row['password'],$row['last_reset'],$row['group_id'], $is_active,$row['created']);

            if ($stmt->execute()) {
                $insertedCount++; // Increment count if insertion is successful
            } else {
                throw new Exception("Failed to insert data into users table: " . $row['id'] ."/n". $stmt->error);
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