<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

function insertBulkDetails($con, $tableName, $tableColumn, $rows) {
    $columns = implode(", ", $tableColumn);
    $values = [];
    $params = [];
    foreach ($rows as $rowIndex => $row) {
        $placeholders = [];
        foreach ($row as $colIndex => $value) {
            $params[] = $value;
            $placeholders[] = '$' . (count($params));
        }
        $values[] = '(' . implode(", ", $placeholders) . ')';
    }
    $conflictColumn = $tableColumn[0]; // Assuming the first column is the unique key column
    $sql = "INSERT INTO $tableName ($columns) VALUES " . implode(", ", $values) . " ON CONFLICT ($conflictColumn) DO NOTHING";
    return pg_query_params($con, $sql, $params);
}

function processFile($con, $filePath, $delimiter, $tableName, $tableColumn, $suppression_id, $maxBatchSize = 1000) {
    $rows = [];
    $handle = fopen($filePath, 'r');
    if ($handle) {
        while (($line = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            $line = array_map('trim', $line);
            if (!empty(array_filter($line))) {
                $params = array_merge($line, [$suppression_id]);
                $rows[] = array_map(function ($value) {
                    return $value === '' ? null : $value;
                }, $params);
                if (count($rows) >= $maxBatchSize) {
                    if (!insertBulkDetails($con, $tableName, $tableColumn, $rows)) {
                        fclose($handle);
                        return ['status' => 'false', 'error' => pg_last_error($con)];
                    }
                    $rows = [];
                }
            }
        }
        fclose($handle);
        if (!empty($rows)) {
            if (!insertBulkDetails($con, $tableName, $tableColumn, $rows)) {
                return ['status' => 'false', 'error' => pg_last_error($con)];
            }
        }
        return ['status' => 'true'];
    } else {
        return ['status' => 'false', 'error' => 'Error opening the file'];
    }
}

if ($type == 'addSuppression') {
    $required_params = ['suppression_type'];

    foreach ($required_params as $param) {
        if (!isset($_REQUEST[$param])) {
            echo json_encode(['status' => 'false', 'error' => 'Missing parameters']);
            exit();
        }
    }

    $suppression_type = $_REQUEST['suppression_type'];
    $suppression_file_name = $_FILES['file']['name'];

    $suppression_types = [
        "Offer suppression file",
        "Bounce file",
        "Complaint file",
        "FBL file",
        "Opt Out file",
        "Unsubscribe file",
        "Esp Bounce file"
    ];

    if (!in_array($suppression_type, $suppression_types)) {
        echo json_encode(['status' => 'false', 'error' => 'Invalid Suppression Type']);
        exit();
    }

    $insertResult = pg_query_params(
        $con,
        "INSERT INTO tbl_suppression (suppression_type, suppression_file_name, create_by, c_date) VALUES ($1, $2, $3, $4) RETURNING suppression_id",
        [$suppression_type, $suppression_file_name, $create_by, $c_date]
    );

    if ($insertResult) {
        $insertedRow = pg_fetch_assoc($insertResult);
        $suppression_id = $insertedRow['suppression_id'];

        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            switch ($suppression_type) {
                case "Offer suppression file":
                case "Bounce file":
                case "Complaint file":
                case "Esp Bounce file":
                    $tableName = "tbl_" . strtolower(str_replace(" ", "_", $suppression_type));
                    $tableColumn = ["mail_id", "suppression_id"];
                    $ifCheck = true;
                    break;
                case "FBL file":
                case "Opt Out file":
                case "Unsubscribe file":
                    $tableName = "tbl_" . strtolower(str_replace(" ", "_", $suppression_type));
                    $tableColumn = ["emid", "offer_id", "suppression_id"];
                    $ifCheck = false;
                    break;
                default:
                    echo json_encode(['status' => 'false', 'error' => 'Unhandled Suppression Type']);
                    exit();
            }

            if ($ext == 'txt') {
                $data = processFile($con, $_FILES['file']['tmp_name'], '|', $tableName, $tableColumn, $suppression_id);
            } elseif ($ext == 'csv') {
                $csvMimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];
                if (in_array($_FILES['file']['type'], $csvMimes)) {
                    $data = processFile($con, $_FILES['file']['tmp_name'], ',', $tableName, $tableColumn, $suppression_id);
                } else {
                    $data = array('status' => 'false', 'error' => 'Invalid file type or no file uploaded');
                }
            } else {
                $data = array('status' => 'false', 'error' => 'Invalid file type');
            }
        } else {
            $data = array('status' => 'false', 'error' => 'File upload error');
        }
    } else {
        $data = array('status' => 'false', 'error' => pg_last_error($con));
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
        $sql .= " AND (suppression_type ILIKE $1 OR suppression_file_name ILIKE $2)";
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
        $sub_array[] = $row['suppression_type'];
        $sub_array[] = $row['suppression_file_name'];

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
        $deleteSubTable = pg_query_params($con, "DELETE FROM tbl_offer_suppression_file  WHERE suppression_id=$1", array($d_id));
        $data = array('status' => 'true');
    } else {
        $data = array('status' => 'false');
    }
    echo json_encode($data);
}
