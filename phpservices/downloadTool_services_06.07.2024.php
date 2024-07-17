<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

if ($type == 'addDownloadTool') {
    
    $required_params = ['isp_type', 'group_type', 'list_id', 'sub_seg_id', 'seg_id', 'fbl_type', 'offer_suppression_id', 'esp_bounce_type', 'unique_type', 'dnd_type'];
    $missing_params = array_diff($required_params, array_keys($_REQUEST));

    if (!empty($missing_params)) {
        echo json_encode(['status' => 'false', 'error' => 'Missing Parameters']);
        exit();
    }

    $params = array_intersect_key($_REQUEST, array_flip($required_params));
    extract($params);

    $isp_types = ["yahoo", "comcast", "hotmail", "gmail", "aol"];
    $isp_map = ["yahoo" => "ya", "comcast" => "cc", "hotmail" => "hot", "gmail" => "gm", "aol" => "aol"];
    $group_types = ["all_yahoo", "all_comcast", "all_hotmail", "all_gmail", "all_aol"];

    if (!in_array($isp_type, $isp_types) || !in_array($group_type, $group_types)) {
        echo json_encode(['status' => 'false', 'error' => 'Invalid Type']);
        exit();
    }

    $add_f = ($fbl_type == 'Yes') ? 'f' : '';
    $add_eb = ($esp_bounce_type == 'Yes') ? '_eb' : '';
    $add_isp = $isp_map[$isp_type];

    $data_file_name = "{$add_isp}_{$list_id}{$sub_seg_id}{$seg_id}";
    $suppression_file_name = "{$add_isp}_{$list_id}{$sub_seg_id}{$seg_id}_obc{$add_f}{$add_eb}_{$offer_suppression_id}";

    $insertResult = pg_query_params(
        $con,
        "INSERT INTO tbl_download_tool (isp_type, group_type, list_id, sub_seg_id, seg_id, fbl_type, offer_suppression_id, esp_bounce_type, unique_type, dnd_type, data_file_name, suppression_file_name, status, create_by, c_date) 
         VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15) 
         RETURNING download_id",
        [$isp_type, $group_type, $list_id, $sub_seg_id, $seg_id, $fbl_type, $offer_suppression_id, $esp_bounce_type, $unique_type, $dnd_type, $data_file_name, $suppression_file_name, 'Pending', $create_by, $c_date]
    );

    if ($insertResult) {
        $download_id = pg_fetch_result($insertResult, 0, 'download_id');
        $data = ['status' => 'true', 'id' => $download_id];
    } else {
        $data = ['status' => 'false', 'error' => pg_last_error($con)];
    }

    echo json_encode($data);
    exit();
}

if ($type == 'getDownloadTool') {
    $output = array();
    $sql = "SELECT * FROM tbl_download_tool ";
    $param = array();

    $totalQuery = pg_query($con, $sql);
    $total_all_rows = pg_num_rows($totalQuery);

    if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search_value = $_POST['search']['value'];
        $sql .= " WHERE (data_file_name ILIKE $1 OR suppression_file_name ILIKE $2)";
        $param[] = "%$search_value%";
        $param[] = "%$search_value%";
    }

    $sql .= " ORDER BY download_id DESC";

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
        $sub_array[] = $row['fbl_type'];

        $creator_query = pg_query_params($con, "SELECT staff_name FROM tbl_staff WHERE staff_id=$1", array($row['create_by']));
        $creator_info = pg_fetch_assoc($creator_query);

        $last_updated = '<span class="badge badge-info">Created by ' . $creator_info['staff_name'] . ' on<br>' . $row['c_date'] . '</span>';
        $sub_array[] = $last_updated;

        $sub_array[] = $row['data_file_name'] . '.txt <br> <b>Count :</b> ' . $row['data_file_count'] . ', <b>ID</b> : ' . $row['offer_suppression_id'];
        $sub_array[] = $row['suppression_file_name'] . ' <br> <b>Count :</b> ' . $row['suppression_file_count'] . '';
        $button_status = ($row['status'] == 'Completed') ? 'success' : 'danger';
        $sub_array[] = '<button type="button" class="btn btn-' . $button_status . '">' . $row['status'] . '</button>';

        $action_btn = '<button type="button" data-toggle="modal" data-target="#downloadStatusPopup" data-row=' . json_encode($row) . ' data-value="' . $i . '" class="btn btn-danger m-r-5 mb-1 downloadStatusBtnEmail"><i class="icon ti-search" style="font-size: 16px!important;color: white!important;"></i></button>';
        $sub_array[] = $action_btn;

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

if ($type == 'downloadTool') {
    $start_time = microtime(true);
    $download_tool_id = $_POST['id'];

    $sqlDowToolFilter = "SELECT * 
                          FROM tbl_download_tool 
                          WHERE download_id = $1";
    $queryDowToolFilter = pg_query_params($con, $sqlDowToolFilter, [$download_tool_id]);

    if (!$queryDowToolFilter) {
        echo json_encode(['status' => 'false', 'error' => pg_last_error($con)]);
        exit();
    }
    $rowDowToolFilter = pg_fetch_assoc($queryDowToolFilter);

    $list_id = $rowDowToolFilter['list_id'];
    $sub_seg_id = $rowDowToolFilter['sub_seg_id'];
    $seg_id = $rowDowToolFilter['seg_id'];
    $isp_type = $rowDowToolFilter['isp_type'];
    $offer_suppression_id = $rowDowToolFilter['offer_suppression_id'];
    $fbl_type = $rowDowToolFilter['fbl_type'];
    $esp_bounce_type = $rowDowToolFilter['esp_bounce_type'];
    $suppression_file_name = $rowDowToolFilter['suppression_file_name'];
    $unique_type = $rowDowToolFilter['unique_type'];

    $sqlDataFileFilter = "SELECT COUNT(email_details_id) AS table_count 
                          FROM tbl_email_details 
                          WHERE delete_status != '1' AND ds = $1 AND isp_type = $2";
    $queryDataFileFilter = pg_query_params($con, $sqlDataFileFilter, ["{$list_id}{$sub_seg_id}{$seg_id}", $isp_type]);

    if (!$queryDataFileFilter) {
        echo json_encode(['status' => 'false', 'error' => pg_last_error($con)]);
        exit();
    }

    $rowDataFileFilter = pg_fetch_assoc($queryDataFileFilter);
    $dataFileFilterCount = $rowDataFileFilter['table_count'];

    $sqlOfferSuppFilter = "SELECT STRING_AGG(t2.mail_id::text, ',') AS hash_ids 
                           FROM tbl_suppression t1 
                           LEFT JOIN tbl_offer_suppression_file t2 ON t1.suppression_id = t2.suppression_id 
                           WHERE t1.delete_status != '1' AND suppression_file_name ILIKE $1";
    $queryOfferSuppFilter = pg_query_params($con, $sqlOfferSuppFilter, ["{$offer_suppression_id}suppression%"]);

    if (!$queryOfferSuppFilter) {
        echo json_encode(['status' => 'false', 'error' => pg_last_error($con)]);
        exit();
    }

    $rowOfferSuppFilter = pg_fetch_assoc($queryOfferSuppFilter);
    $dataOfferSuppFilterIds = array_flip(explode(',', $rowOfferSuppFilter['hash_ids']));

    $query_fbl = ($fbl_type == 'Yes') ? 'LEFT JOIN tbl_fbl_file t2 ON t1.emid = t2.emid' : '';
    $where_fbl = ($fbl_type == 'Yes') ? 'AND t2.emid IS NULL' : '';
    $select_fbl = ($fbl_type == 'Yes') ? ', COUNT(DISTINCT t2.emid) AS fbl_count' : '';

    $query_esp = ($esp_bounce_type == 'Yes') ? 'LEFT JOIN tbl_esp_bounce_file t3 ON t1.email = t3.mail_id' : '';
    $where_esp = ($esp_bounce_type == 'Yes') ? 'AND t3.mail_id IS NULL' : '';
    $select_esp = ($esp_bounce_type == 'Yes') ? ', COUNT(DISTINCT t3.mail_id) AS esp_count' : '';

    $sqlFblEspSuppFilter = "SELECT 
                STRING_AGG(DISTINCT CASE 
                    WHEN 
                        t4.mail_id IS NULL AND 
                        t5.mail_id IS NULL
                          $where_fbl
                          $where_esp
                    THEN t1.email 
                    ELSE NULL 
                END, ',') AS email_ids,
                STRING_AGG(DISTINCT CASE 
                    WHEN 
                        t4.mail_id IS NULL AND 
                        t5.mail_id IS NULL 
                          $where_fbl
                          $where_esp
                    THEN t1.email_details_id::text 
                    ELSE NULL 
                END, ',') AS table_ids, 
                COUNT(DISTINCT t4.mail_id) AS bounce_count,
                COUNT(DISTINCT t5.mail_id) AS complaint_count
                $select_fbl
                $select_esp
            FROM 
                tbl_email_details t1 
            LEFT JOIN tbl_bounce_file t4 ON t1.email = t4.mail_id
            LEFT JOIN tbl_complaint_file t5 ON t1.email = t5.mail_id
            $query_fbl
            $query_esp
            WHERE 
                t1.delete_status != '1' AND 
                t1.ds = $1 AND 
                t1.isp_type = $2;
            ";
    $queryFblEspSuppFilter = pg_query_params($con, $sqlFblEspSuppFilter, ["{$list_id}{$sub_seg_id}{$seg_id}", $isp_type]);

    if (!$queryFblEspSuppFilter) {
        echo json_encode(['status' => 'false', 'error' => pg_last_error($con)]);
        exit();
    }

    $rowFblEspSuppFilter = pg_fetch_assoc($queryFblEspSuppFilter);

    $dataFblEspSuppFilterIds = explode(',', $rowFblEspSuppFilter['email_ids']);
    $dataFblEspSuppFilterTableIds = explode(',', $rowFblEspSuppFilter['table_ids']);

    $fblSuppCount = $rowFblEspSuppFilter['fbl_count'] ?? 0;
    $espSuppCount = $rowFblEspSuppFilter['esp_count'] ?? 0;
    $bounceSuppCount = $rowFblEspSuppFilter['bounce_count'] ?? 0;
    $complaintSuppCount = $rowFblEspSuppFilter['complaint_count'] ?? 0;

    $no_match_table_ids = [];
    $match_table_ids = [];

    foreach ($dataFblEspSuppFilterIds as $index => $email) {
        $hashed_email = md5($email);
        if (!isset($dataOfferSuppFilterIds[$hashed_email])) {
            $no_match_table_ids[] = $dataFblEspSuppFilterTableIds[$index];
        } else {
            $match_table_ids[] = $dataOfferSuppFilterIds[$hashed_email];
        }
    }

    $suppFileFilterCount = count($no_match_table_ids);
    $suppFileFilterIds = implode(',', $no_match_table_ids);
    $offerSuppCount = count($match_table_ids);

    $if_unique = ($unique_type == 'Yes') ? 'DISTINCT ON (email)' : '';

    if ($suppFileFilterIds != '') {
        $sql = "SELECT $if_unique emid, email, ds, isp_type, edate, e_ip, fname, lname, suburl, subdate, click, open, flag FROM tbl_email_details WHERE delete_status != '1' AND email_details_id IN ($suppFileFilterIds) ORDER BY email, emid";
        $result = pg_query($con, $sql);

        if (!$result) {
            $data = array('status' => 'false', 'error' => pg_last_error($con));
        } else {
            $file_path = '../' . $suppression_file_name . '.txt';
            $file = fopen($file_path, 'w');
            if ($file) {
                $fileContent = '';
                while ($row = pg_fetch_assoc($result)) {
                    $line = implode('|', [
                        $row['emid'],
                        $row['email'],
                        $row['ds'],
                        $row['isp_type'],
                        $row['edate'],
                        $row['e_ip'],
                        $row['fname'],
                        $row['lname'],
                        $row['suburl'],
                        $row['subdate'],
                        $row['click'],
                        $row['open'],
                        $row['flag'],
                    ]);
                    $fileContent .= $line . PHP_EOL;
                }

                fwrite($file, $fileContent);
                fclose($file);

                $end_time = microtime(true);
                $execution_time = $end_time - $start_time;

                $updateDownloadTool = pg_query_params(
                    $con,
                    "UPDATE tbl_download_tool 
         SET data_file_count=$1, suppression_file_count=$2, execution_time=$3, offer_suppression_count=$4, fbl_suppression_count=$5, esp_suppression_count=$6, bounce_suppression_count=$7, complaint_suppression_count=$8, status=$9
         WHERE download_id=$10",
                    [$dataFileFilterCount, $suppFileFilterCount, $execution_time, $offerSuppCount, $fblSuppCount, $espSuppCount, $bounceSuppCount, $complaintSuppCount, 'Completed', $download_tool_id]
                );

                if ($updateDownloadTool) {
                    $data = array('status' => 'true');
                } else {
                    $data = array('status' => 'false', 'error' => pg_last_error($con));
                }
            } else {
                $data = array('status' => 'false', 'error' => 'Cannot open file for writing');
            }
        }
    } else {
        $data = array('status' => 'false', 'error' => 'Data is Empty');
    }
    echo json_encode($data);
    exit();
}
