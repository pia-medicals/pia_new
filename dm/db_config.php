<?php
// Old database connection (MySQL 5.x)
//$old_db = new mysqli("localhost", "root", "", "dicondbone_old");
$old_db = new mysqli("localhost", "root", "W@IIZT-S262fL[Yz", "piament");

// New database connection (MySQL 8.x)
//$new_db = new mysqli("localhost", "root", "", "dicondbone_new");
$new_db = new mysqli("localhost", "root", "W@IIZT-S262fL[Yz", "newdicon");

// Check old DB connection
if ($old_db->connect_error) {
    die("Connection to old database failed: " . $old_db->connect_error);
}

// Check new DB connection
if ($new_db->connect_error) {
    die("Connection to new database failed: " . $new_db->connect_error);
}

?>
