<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

if ($type == 'addSuppression') {
    $required_params = ['offer_sup_file', 'bounce_file', 'complaint_file', 'fbl_file', 'opt_out_file', 'unsub_file', 'esp_bounce_file'];

    foreach ($required_params as $param) {
        if (!isset($_REQUEST[$param])) {
            echo json_encode(array('status' => 'missing_parameters'));
            exit();
        }
    }

    $offer_sup_file = $_REQUEST['offer_sup_file'];
    $bounce_file = $_REQUEST['bounce_file'];
    $complaint_file = $_REQUEST['complaint_file'];
    $fbl_file = $_REQUEST['fbl_file'];
    $opt_out_file = $_REQUEST['opt_out_file'];
    $unsub_file = $_REQUEST['unsub_file'];
    $esp_bounce_file = $_REQUEST['esp_bounce_file'];

    $insertResult = pg_query_params(
        $con,
        "INSERT INTO tbl_suppression (offer_sup_file, bounce_file, complaint_file, fbl_file, opt_out_file, unsub_file, esp_bounce_file, create_by, c_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)",
        array($offer_sup_file, $bounce_file, $complaint_file, $fbl_file, $opt_out_file, $unsub_file, $esp_bounce_file, $create_by, $c_date)
    );
    if ($insertResult) {
        $data = array('status' => 'true');
    } else {
        $data = array('status' => 'false');
    }

    echo json_encode($data);
    exit();
}

if ($type == 'getSuppression') {
    $output = array();
    $sql = "SELECT * FROM tbl_suppression WHERE delete_status != '1'";
    $param = array();

    $totalQuery = pg_query($con, $sql);
    $total_all_rows = pg_num_rows($totalQuery);

    if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search_value = $_POST['search']['value'];
        $sql .= " AND (offer_sup_file ILIKE $1 OR bounce_file ILIKE $2 OR complaint_file ILIKE $3)";
        $param[] = "%$search_value%";
        $param[] = "%$search_value%";
        $param[] = "%$search_value%";
    }

    $sql .= " ORDER BY suppression_id DESC";

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
        $sub_array[] = $row['offer_sup_file'];
        $sub_array[] = $row['bounce_file'];
        $sub_array[] = $row['complaint_file'];

        if ($row['edit_by'] === null) {
            $creator_query = pg_query_params($con, "SELECT staff_name FROM staff WHERE id=$1", array($row['create_by']));
            $creator_info = pg_fetch_assoc($creator_query);
            $last_updated = '<span class="badge badge-success">Created by ' . $creator_info['staff_name'] . ' on<br>' . $row['c_date'] . '</span>';
        } else {
            $editor_query = pg_query_params($con, "SELECT staff_name FROM staff WHERE id=$1", array($row['edit_by']));
            $editor_info = pg_fetch_assoc($editor_query);
            $last_updated = '<span class="badge badge-warning text-white">Edited by ' . $editor_info['staff_name'] . ' on<br>' . $row['e_date'] . '</span>';
        }
        $sub_array[] = $last_updated;

        $edit_delete_btn = '';
        if ($menu_emp['master_upd'] == '1') {
            $edit_delete_btn .= '<button type="button" data-id="' . $row['suppression_id'] . '" data-value="' . $i . '" class="btn btn-warning m-r-5 mb-1 editBtnSuppression"><i class="icon ti-pencil-alt" style="font-size: 16px!important;color: white!important;"></i></button>';
        }
        if ($menu_emp['master_del'] == '1') {
            $edit_delete_btn .= '<button type="button" data-id="' . $row['suppression_id'] . '" data-value="' . $i . '" class="btn btn-danger m-r-5 mb-1 deleteBtnSuppression"><i class="icon ti-trash" style="font-size: 16px!important;color: white!important;"></i></button>';
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

if ($type == 'deleteSuppression') {
    $d_id = $_REQUEST['d_id'];
    $delete = pg_query_params($con, "UPDATE tbl_suppression SET delete_status='1', delete_by=$1, d_date=$2 WHERE suppression_id=$3", array($delete_by, $d_date, $d_id));
    if ($delete) {
        $data = array('status' => 'true');
    } else {
        $data = array('status' => 'false');
    }
    echo json_encode($data);
}

if ($type == 'editSuppression') {
    $edit_id = $_REQUEST['id'];
    $edit = pg_query_params($con, "SELECT * FROM tbl_suppression WHERE suppression_id=$1", array($edit_id));
    $edit1 = pg_fetch_assoc($edit);
    echo json_encode($edit1);
}

if ($type == 'updateSuppression') {
    $required_params = ['offer_sup_file', 'bounce_file', 'complaint_file', 'fbl_file', 'opt_out_file', 'unsub_file', 'esp_bounce_file'];

    foreach ($required_params as $param) {
        if (!isset($_REQUEST[$param])) {
            echo json_encode(array('status' => 'missing_parameters'));
            exit();
        }
    }

    $offer_sup_file = $_REQUEST['offer_sup_file'];
    $bounce_file = $_REQUEST['bounce_file'];
    $complaint_file = $_REQUEST['complaint_file'];
    $fbl_file = $_REQUEST['fbl_file'];
    $opt_out_file = $_REQUEST['opt_out_file'];
    $unsub_file = $_REQUEST['unsub_file'];
    $esp_bounce_file = $_REQUEST['esp_bounce_file'];

    $updateResult = pg_query_params($con, "UPDATE tbl_suppression SET offer_sup_file=$1, bounce_file=$2, complaint_file=$3, fbl_file=$4, opt_out_file=$5, unsub_file=$6, esp_bounce_file=$7, edit_by=$8, e_date=$9 WHERE email_details_id=$10", array($offer_sup_file, $bounce_file, $complaint_file, $fbl_file, $opt_out_file, $unsub_file, $esp_bounce_file, $edit_by, $e_date, $edit_id));

    if ($updateResult) {
        $data = array('status' => 'true');
    } else {
        $data = array('status' => 'false');
    }

    echo json_encode($data);
}
