<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

if ($type == 'addEmail') {
    $required_params = ['emid', 'email', 'ds', 'isp_type', 'edate', 'e_ip', 'fname', 'lname', 'suburl', 'subdate', 'click', 'open', 'flag'];

    foreach ($required_params as $param) {
        if (!isset($_REQUEST[$param])) {
            echo json_encode(array('status' => 'false', 'error' => 'Missing Parameters'));
            exit();
        }
    }

    $emid = $_REQUEST['emid'];
    $email = $_REQUEST['email'];
    $ds = $_REQUEST['ds'];
    $isp_type = $_REQUEST['isp_type'];
    $edate = date('Y-m-d', strtotime($_REQUEST['edate']));
    $e_ip = $_REQUEST['e_ip'];
    $fname = $_REQUEST['fname'];
    $lname = $_REQUEST['lname'];
    $suburl = $_REQUEST['suburl'];
    $subdate = date('Y-m-d', strtotime($_REQUEST['subdate']));
    $click = date('Y-m-d', strtotime($_REQUEST['click']));
    $open = date('Y-m-d', strtotime($_REQUEST['open']));
    $flag = $_REQUEST['flag'];

    $flags = ["Active", "Bounce", "Complaint", "Unsubscribe"];

    if (!in_array($flag, $flags)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid flag'));
        exit();
    }

    $isp_types = ["yahoo", "comcast", "hotmail", "gmail", "aol"];

    if (!in_array($isp_type, $isp_types)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid isp type'));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid email'));
        exit();
    }

    $checkResult = pg_query_params($con, "SELECT id FROM tbl_email_details WHERE emid = $1 AND email = $2 AND delete_status = '0'", array($emid, $email));
    if ($checkResult && pg_num_rows($checkResult) > 0) {
        $data = array('status' => 'exists');
    } else {
        $insertResult = pg_query_params(
            $con,
            "INSERT INTO tbl_email_details (emid, email, ds, isp_type, edate, e_ip, fname, lname, suburl, subdate, click, open, flag, create_by, c_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)",
            array($emid, $email, $ds, $isp_type, $edate, $e_ip, $fname, $lname, $suburl, $subdate, $click, $open, $flag, $create_by, $c_date)
        );
        if ($insertResult) {
            $data = array('status' => 'true');
        } else {
            $data = array('status' => 'false', 'error' => 'An error occurred');
            echo "Error: " . pg_last_error($con);
        }
    }

    echo json_encode($data);
    exit();
}

if ($type == 'getEmail') {
    $output = array();
    $sql = "SELECT * FROM tbl_email_details WHERE delete_status != '1'";
    $param = array();

    $totalQuery = pg_query($con, $sql);
    $total_all_rows = pg_num_rows($totalQuery);

    if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search_value = $_POST['search']['value'];
        $sql .= " AND (emid ILIKE $1 OR email ILIKE $2 OR ds ILIKE $3)";
        $param[] = "%$search_value%";
        $param[] = "%$search_value%";
        $param[] = "%$search_value%";
    }

    $sql .= " ORDER BY email_details_id DESC";

    if ($_POST['length'] != -1) {
        $start = $_POST['start'];
        $length = $_POST['length'];
        $sql .= " LIMIT $" . (count($param) + 1) . " OFFSET $" . (count($param) + 2);
        $param[] = $length;
        $param[] = $start;
    }

    $query = pg_query_params($con, $sql, $param);
    $data = array();
    $i = $_POST['start'] + 1;

    while ($row = pg_fetch_assoc($query)) {
        $sub_array = array();
        $sub_array[] = $i;
        $sub_array[] = $row['emid'];
        $sub_array[] = $row['email'];
        $sub_array[] = $row['ds'];

        if ($row['edit_by'] === null) {
            $creator_query = pg_query_params($con, "SELECT staff_name FROM tbl_staff WHERE staff_id=$1", array($row['create_by']));
            $creator_info = pg_fetch_assoc($creator_query);
            $last_updated = '<span class="badge badge-success">Created by ' . $creator_info['staff_name'] . ' on<br>' . $row['c_date'] . '</span>';
        } else {
            $editor_query = pg_query_params($con, "SELECT staff_name FROM tbl_staff WHERE staff_id=$1", array($row['edit_by']));
            $editor_info = pg_fetch_assoc($editor_query);
            $last_updated = '<span class="badge badge-warning text-white">Edited by ' . $editor_info['staff_name'] . ' on<br>' . $row['e_date'] . '</span>';
        }
        $sub_array[] = $last_updated;

        $edit_delete_btn = '';
        if ($menu_emp['master_upd'] == '1') {
            $edit_delete_btn .= '<button type="button" data-id="' . $row['email_details_id'] . '" data-value="' . $i . '" class="btn btn-warning m-r-5 mb-1 editBtnEmail"><i class="icon ti-pencil-alt" style="font-size: 16px!important;color: white!important;"></i></button>';
        }
        if ($menu_emp['master_del'] == '1') {
            $edit_delete_btn .= '<button type="button" data-id="' . $row['email_details_id'] . '" data-value="' . $i . '" class="btn btn-danger m-r-5 mb-1 deleteBtnEmail"><i class="icon ti-trash" style="font-size: 16px!important;color: white!important;"></i></button>';
        }
        $sub_array[] = $edit_delete_btn;

        $data[] = $sub_array;
        $i++;
    }

    $output = array(
        'draw' => intval($_POST['draw']),
        'recordsTotal' => $total_all_rows,
        'recordsFiltered' => $total_all_rows,
        'data' => $data,
    );

    echo json_encode($output);
    exit();
}

if ($type == 'deleteEmail') {
    $d_id = $_REQUEST['d_id'];
    $delete = pg_query_params($con, "UPDATE tbl_email_details SET delete_status='1', delete_by=$1, d_date=$2 WHERE email_details_id=$3", array($delete_by, $d_date, $d_id));
    if ($delete) {
        $data = array('status' => 'true');
    } else {
        $data = array('status' => 'false');
    }
    echo json_encode($data);
}

if ($type == 'editEmail') {
    $edit_id = $_REQUEST['id'];
    $edit = pg_query_params($con, "SELECT * FROM tbl_email_details WHERE email_details_id=$1", array($edit_id));
    $edit1 = pg_fetch_assoc($edit);
    echo json_encode($edit1);
}

if ($type == 'updateEmail') {
    $required_params = ['emid', 'email', 'ds', 'isp_type', 'edate', 'e_ip', 'fname', 'lname', 'suburl', 'subdate', 'click', 'open', 'flag'];

    foreach ($required_params as $param) {
        if (!isset($_REQUEST[$param])) {
            echo json_encode(array('status' => 'false', 'error' => 'Missing Parameters'));
            exit();
        }
    }

    $emid = $_REQUEST['emid'];
    $email = $_REQUEST['email'];
    $ds = $_REQUEST['ds'];
    $isp_type = $_REQUEST['isp_type'];
    $edate = date('Y-m-d', strtotime($_REQUEST['edate']));
    $e_ip = $_REQUEST['e_ip'];
    $fname = $_REQUEST['fname'];
    $lname = $_REQUEST['lname'];
    $suburl = $_REQUEST['suburl'];
    $subdate = date('Y-m-d', strtotime($_REQUEST['subdate']));
    $click = date('Y-m-d', strtotime($_REQUEST['click']));
    $open = date('Y-m-d', strtotime($_REQUEST['open']));
    $flag = $_REQUEST['flag'];
    $edit_id = $_REQUEST['edit_id'];

    $flags = ["Active", "Bounce", "Complaint", "Unsubscribe"];

    if (!in_array($flag, $flags)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid flag'));
        exit();
    }

    $isp_types = ["yahoo", "comcast", "hotmail", "gmail", "aol"];

    if (!in_array($isp_type, $isp_types)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid isp type'));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('status' => 'false', 'error' => 'Invalid email'));
        exit();
    }

    $checkResult = pg_query_params($con, "SELECT id FROM tbl_email_details WHERE emid = $1 AND email = $2 AND delete_status = '0' AND email_details_id != $3", array($emid, $email, $edit_id));
    if ($checkResult && pg_num_rows($checkResult) > 0) {
        $data = array('status' => 'exists');
    } else {
        $updateResult = pg_query_params($con, "UPDATE tbl_email_details SET emid=$1, email=$2, ds=$3, isp_type=$4, edate=$5, e_ip=$6, fname=$7, lname=$8, suburl=$9, subdate=$10, click=$11, open=$12, flag=$13, edit_by=$14, e_date=$15 WHERE email_details_id=$16", array($emid, $email, $ds, $isp_type, $edate, $e_ip, $fname, $lname, $suburl, $subdate, $click, $open, $flag, $edit_by, $e_date, $edit_id));

        if ($updateResult) {
            $data = array('status' => 'true');
        } else {
            $data = array('status' => 'false', 'error' => 'An error occurred');
            echo "Error: " . pg_last_error($con);
        }
    }
    echo json_encode($data);
}

if ($type == 'uploadEmail') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        function insertEmailDetails($con, $params) {
            $sql = "INSERT INTO tbl_email_details (emid, email, ds, isp_type, edate, e_ip, fname, lname, suburl, subdate, click, open, flag, create_by, c_date) 
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";
            return pg_query_params($con, $sql, $params);
        }

        if ($ext == 'txt') {
            $fh = fopen($_FILES['file']['tmp_name'], 'r');
            if ($fh) {
                $error_occurred = false;
                while (!feof($fh)) {
                    $line_of_text = fgets($fh);
                    if (trim($line_of_text) !== "") {
                        $parts = array_map('trim', explode('|', $line_of_text));
                        if (count($parts) >= 13) {
                            $parts[12] = 'Active';
                            $params = array_merge($parts, [$create_by, $c_date]);
                            $params = array_map(function($value) { return $value === '' ? null : $value; }, $params);
                            if (!insertEmailDetails($con, $params)) {
                                $error_occurred = true;
                                $data = array('status' => 'false', 'error' => pg_last_error($con));
                                break;
                            }
                        } else {
                            $data = array('status' => 'false', 'error' => 'Missing Parameters');
                            break;
                        }
                    }
                }
                fclose($fh);
                if (!$error_occurred) $data = array('status' => 'true');
            } else {
                $data = array('status' => 'false', 'error' => 'Error opening the file');
            }
        } elseif ($ext == 'csv') {
            $csvMimes = array('text/csv', 'application/csv', 'application/vnd.ms-excel');
            if (in_array($_FILES['file']['type'], $csvMimes)) {
                $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                if ($csvFile) {
                    fgetcsv($csvFile); // Skip header
                    $error_occurred = false;
                    while (($line = fgetcsv($csvFile)) !== FALSE) {
                        $line_arr = array_map('trim', $line);
                        if (!empty(array_filter($line_arr))) {
                            $params = [
                                $line_arr[0] ?? null, 
                                $line_arr[1] ?? null,
                                $line_arr[2] ?? null, 
                                $line_arr[3] ?? null,
                                $line_arr[4] ? date('Y-m-d', strtotime($line_arr[4])) : null, 
                                $line_arr[5] ?? null, 
                                $line_arr[6] ?? null,
                                $line_arr[7] ?? null, 
                                $line_arr[8] ?? null, 
                                $line_arr[9] ? date('Y-m-d', strtotime($line_arr[9])) : null, 
                                $line_arr[10] ? date('Y-m-d', strtotime($line_arr[10])) : null, 
                                $line_arr[11] ? date('Y-m-d', strtotime($line_arr[11])) : null, 
                                'Active', 
                                $create_by, 
                                $c_date
                            ];
                            if (!insertEmailDetails($con, $params)) {
                                $error_occurred = true;
                                $data = array('status' => 'false', 'error' => pg_last_error($con));
                                break;
                            }
                        }
                    }
                    fclose($csvFile);
                    if (!$error_occurred) $data = array('status' => 'true');
                } else {
                    $data = array('status' => 'false', 'error' => 'Error opening CSV file');
                }
            } else {
                $data = array('status' => 'false', 'error' => 'Invalid file type or no file uploaded');
            }
        } else {
            $data = array('status' => 'false', 'error' => 'Invalid file type');
        }
    } else {
        $data = array('status' => 'false', 'error' => 'File upload error');
    }
    echo json_encode($data);
    exit();
}
