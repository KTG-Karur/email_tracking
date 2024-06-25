<?php
error_reporting(0);
include('config.php');
session_start();

$username = $_POST['user'];
$query = "SELECT * FROM tbl_staff WHERE user_name = $1 AND password = $2 AND status = '1'";
$params = array($_POST['user_name'], $_POST['pass']);
$result = pg_query_params($con, $query, $params);

if (pg_num_rows($result) > 0) {
    $userresultset1 = pg_fetch_array($result);
    if ($userresultset1['status'] != '0') {
        $query_emp = "SELECT * FROM tbl_emp_rights WHERE staff_id = $1";
        $params_emp = array($userresultset1['staff_id']);
        $menu_emp = pg_query_params($con, $query_emp, $params_emp);
        if (!$menu_emp) {
            die(pg_last_error($con));
        }
        $menu_emp1 = pg_fetch_array($menu_emp);
        
        $_SESSION['memid'] = $userresultset1['staff_id'];
        $_SESSION['type'] = $userresultset1['role'];
        $_SESSION['user_name'] = $userresultset1['staff_name'];
        $_SESSION['emp_rights'] = $menu_emp1;

        $data = array('status' => 'true');
        echo json_encode($data);
    } else {
        $data = array('status' => 'pending');
        echo json_encode($data);
    }
} else {
    $data = array('status' => 'false');
    echo json_encode($data);
}
?>
