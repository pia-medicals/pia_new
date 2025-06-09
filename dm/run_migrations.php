<?php
ini_set('max_execution_time', 30000); // 5 minutes
ini_set('memory_limit', '51222M');

// Include the migration files for each table
$files = [
//    'migrate_user_type.php',
//    'migrate_users.php',
//    'migrate_clients.php',
//    'migrate_client_details.php',
//    'migrate_analyses_category.php',
//    'migrate_analyses.php',
 // 'migrate_analyses_client_price_details.php',
 //   'migrate_monthly_volume_discount.php',
    //'migrate_monthly_fees.php',
 //   'migrate_subscription.php',
  //    'migrate_subscription_contents.php',
 //   'migrate_maintenance_fees.php',
//   'migrate_webhook_temp.php',
//    'migrate_analysis_status.php',
//    'migrate_miscellaneous_billing.php',
      'migrate_studies.php',
  //  'migrate_analyses_performed.php'
];

// Run each migration file
foreach ($files as $file) {
    echo "</br></br>"."Running migration: $file</br>";
    include $file;  // Include and execute the migration file
}

echo "All migrations completed.";

// "http://localhost/DM/run_migrations.php"
?>
