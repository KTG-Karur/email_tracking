<?php
$server = "localhost";
$username = "postgres";  // Typically 'root' is used for MySQL, in PostgreSQL it's usually 'postgres'
$password = "Ktgt@2011";
$db_name = "postgres_mail_tracking_db"; // Database name

// Create connection
$con = pg_connect("host=$server dbname=$db_name user=$username password=$password");

// Check connection
if (!$con) {
    echo "An error occurred.\n";
    exit();
}

// Start session
session_start();

$menu_emp = $_SESSION['emp_rights'];
$login_type = $_SESSION['type'];
$user_name = $_SESSION['user_name'];
$create_by = $_SESSION['memid'];
$edit_by = $_SESSION['memid'];
$delete_by = $_SESSION['memid'];

date_default_timezone_set('Asia/Kolkata');

$c_date = date('Y-m-d H:i:s');
$e_date = date('Y-m-d H:i:s');
$d_date = date('Y-m-d H:i:s');
?>
