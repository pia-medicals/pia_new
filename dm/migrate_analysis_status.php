<?php 
// Include database configuration
include 'db_config.php';

try {
    // Begin transaction
    $new_db->begin_transaction();

    // Prepare the insert query
    $stmt = $new_db->prepare("INSERT INTO analysis_status (status, status_description, is_active) VALUES (?, ?, ?)");

    // Set the statuses to insert
    $statuses = [
        ['Completed', 'The analysis is complete', '1'],
        ['Under review', 'The analysis is under review', '1'],
        ['In progress', 'The analysis is currently in progress', '1'],
        ['Cancelled', 'The analysis was cancelled', '1'],
        ['On hold', 'The analysis is on hold', '1'],
        ['CancelledAcc', 'The analysis was cancelled by the account', '1'],
        ['CancelledCust', 'The analysis was cancelled by the customer', '1'],
    ];

    // Counter for inserted records
    $insertedCount = 0;

    // Insert each status
    foreach ($statuses as $status) {
        $stmt->bind_param("ssi", $status[0], $status[1], $status[2]);
        
        if ($stmt->execute()) {
            $insertedCount++; // Increment count if insertion is successful
        } else {
            throw new Exception("Failed to insert status: " . $status[0] . " - " . $stmt->error);
        }
    }

    // Commit transaction
    $new_db->commit();

    // Output the count of inserted rows
    echo "Migration successful. Total statuses inserted: " . $insertedCount . "\n";

} catch (Exception $e) {
    // Rollback transaction in case of error
    $new_db->rollback();
    echo "Error during migration: " . $e->getMessage() . "\n";
} finally {
    // Close connections
    $new_db->close();
}
