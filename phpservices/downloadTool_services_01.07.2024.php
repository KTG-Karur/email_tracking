<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

// if ($type == 'addDownloadToll') {
//     $required_params = ['isp_type', 'group_type', 'list_id', 'sub_seg_id', 'seg_id', 'fbl_type', 'offer_suppression_id', 'esp_bounce_type', 'unique_type', 'dnd_type'];

//     foreach ($required_params as $param) {
//         if (!isset($_REQUEST[$param])) {
//             echo json_encode(['status' => 'false', 'error' => 'Missing Parameters']);
//             exit();
//         }
//     }

//     $isp_type = $_REQUEST['isp_type'];
//     $group_type = $_REQUEST['group_type'];
//     $list_id = $_REQUEST['list_id'];
//     $sub_seg_id = $_REQUEST['sub_seg_id'];
//     $seg_id = $_REQUEST['seg_id'];
//     $fbl_type = $_REQUEST['fbl_type'];
//     $offer_suppression_id = $_REQUEST['offer_suppression_id'];
//     $esp_bounce_type = $_REQUEST['esp_bounce_type'];
//     $unique_type = $_REQUEST['unique_type'];
//     $dnd_type = $_REQUEST['dnd_type'];

//     $add_f = ($fbl_type == 'Yes') ? 'f' : '';
//     $add_eb = ($esp_bounce_type == 'Yes') ? '_eb' : '';

//     $isp_types = ["yahoo", "comcast", "hotmail", "gmail", "aol"];
//     if (!in_array($isp_type, $isp_types)) {
//         echo json_encode(['status' => 'false', 'error' => 'Invalid ISP Type']);
//         exit();
//     }

//     switch ($isp_type) {
//         case "yahoo":
//             $add_isp = "ya";
//             break;
//         case "comcast":
//             $add_isp = "cc";
//             break;
//         case "hotmail":
//             $add_isp = "hot";
//             break;
//         case "gmail":
//             $add_isp = "gm";
//             break;
//         case "aol":
//             $add_isp = "aol";
//             break;
//     }

//     $group_types = ["all_yahoo", "all_comcast", "all_hotmail", "all_gmail", "all_aol"];
//     if (!in_array($group_type, $group_types)) {
//         echo json_encode(['status' => 'false', 'error' => 'Invalid Group Type']);
//         exit();
//     }

//     $data_file_name = $add_isp . '_' . $list_id . $sub_seg_id . $seg_id;

//     $suppression_file_name = $add_isp . '_' . $list_id . $sub_seg_id . $seg_id . '_obc' . $add_f . $add_eb . '_' . $offer_suppression_id;

//     $insertResult = pg_query_params(
//         $con,
//         "INSERT INTO tbl_download_toll (isp_type, group_type, list_id, sub_seg_id, seg_id, fbl_type, offer_suppression_id, esp_bounce_type, unique_type, dnd_type, data_file_name, suppression_file_name, create_by, c_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14) RETURNING download_id",
//         [$isp_type, $group_type, $list_id, $sub_seg_id, $seg_id, $fbl_type, $offer_suppression_id, $esp_bounce_type, $unique_type, $dnd_type, $data_file_name, $suppression_file_name, $create_by, $c_date]
//     );

//     if ($insertResult) {
//         $insertedRow = pg_fetch_assoc($insertResult);
//         $download_id = $insertedRow['download_id'];

//         $sqlDataFileFilter = "SELECT Count(email_details_id) as table_count FROM tbl_email_details WHERE delete_status != '1' AND ds = $1 AND isp_type = $2";
//         $queryDataFileFilter = pg_query_params($con, $sqlDataFileFilter, array($list_id . $sub_seg_id . $seg_id, $isp_type));

//         $sqlOfferSuppFilter = "SELECT string_agg(t2.mail_id::text, ',') AS hash_ids FROM tbl_suppression t1 LEFT JOIN tbl_offer_suppression_file t2 ON t1.suppression_id = t2.suppression_id  WHERE t1.delete_status != '1' AND suppression_file_name ILIKE $1 ";
//         $queryOfferSuppFilter = pg_query_params($con, $sqlOfferSuppFilter, array($offer_suppression_id . 'suppression%'));

//         $rowOfferSuppFilter = pg_fetch_assoc($queryOfferSuppFilter);
//         $dataOfferSuppFilterIds = explode(',', $rowOfferSuppFilter['hash_ids']);

//         $query_fbl = ($fbl_type == 'Yes') ? 'LEFT JOIN tbl_fbl_file t2 ON t1.emid = t2.emid' : '';
//         $where_fbl = ($fbl_type == 'Yes') ? 'AND t2.emid IS NULL' : '';

//         $query_esp = ($esp_bounce_type == 'Yes') ? 'LEFT JOIN tbl_esp_bounce_file t3 ON t1.email = t3.mail_id' : '';
//         $where_esp = ($esp_bounce_type == 'Yes') ? 'AND t3.mail_id IS NULL' : '';

//         $sqlFblEspSuppFilter = "SELECT string_agg(email::text, ',') AS email_ids, string_agg(email_details_id::text, ',') AS table_ids FROM tbl_email_details t1 $query_fbl $query_esp WHERE t1.delete_status != '1' AND t1.ds = $1 AND t1.isp_type = $2  $where_fbl $where_esp";
//         $queryFblEspSuppFilter = pg_query_params($con, $sqlFblEspSuppFilter, array($list_id . $sub_seg_id . $seg_id, $isp_type));

//         $rowFblEspSuppFilter = pg_fetch_assoc($queryFblEspSuppFilter);
//         $dataFblEspSuppFilterIds = explode(',', $rowFblEspSuppFilter['email_ids']);
//         $dataFblEspSuppFilterTableIds = explode(',', $rowFblEspSuppFilter['table_ids']);

//         $no_match_table_ids = [];

//         foreach ($dataFblEspSuppFilterIds as $index => $email) {
//             $hashed_email = md5($email);
//             if (!in_array($hashed_email, $dataOfferSuppFilterIds)) {
//                 $no_match_table_ids[] = $dataFblEspSuppFilterTableIds[$index];
//             }
//         }

//         if ($queryDataFileFilter) {
//             $rowDataFileFilter = pg_fetch_assoc($queryDataFileFilter);
//             $dataFileFilterCount = $rowDataFileFilter['table_count'];
//             $suppFileFilterCount = count($no_match_table_ids);
//             $suppFileFilterIds = implode(',', $no_match_table_ids);

//             $updateDownloadToll = pg_query_params($con, "UPDATE tbl_download_toll SET data_file_count=$1, suppression_file_count=$2, download_mail_ids=$3 WHERE download_id=$4", array($dataFileFilterCount, $suppFileFilterCount, $suppFileFilterIds, $download_id));
//             if ($updateDownloadToll) {
//                 $data = array('status' => 'true');
//             } else {
//                 $data = array('status' => 'false', 'error' => pg_last_error($con));
//             }
//         } else {
//             $data = array('status' => 'false', 'error' => pg_last_error($con));
//         }
//     } else {
//         $data = array('status' => 'false', 'error' => pg_last_error($con));
//     }

//     echo json_encode($data);
//     exit();
// }

if ($type == 'addDownloadToll') {
    $start_time = microtime(true);
    $required_params = ['isp_type', 'group_type', 'list_id', 'sub_seg_id', 'seg_id', 'fbl_type', 'offer_suppression_id', 'esp_bounce_type', 'unique_type', 'dnd_type'];
    $missing_params = array_diff($required_params, array_keys($_REQUEST));
    
    if (!empty($missing_params)) {
        echo json_encode(['status' => 'false', 'error' => 'Missing Parameters']);
        exit();
    }
    $params = array_intersect_key($_REQUEST, array_flip($required_params));
    extract($params);
    $add_f = ($fbl_type == 'Yes') ? 'f' : '';
    $add_eb = ($esp_bounce_type == 'Yes') ? '_eb' : '';
    $isp_types = ["yahoo", "comcast", "hotmail", "gmail", "aol"];
    $isp_map = ["yahoo" => "ya", "comcast" => "cc", "hotmail" => "hot", "gmail" => "gm", "aol" => "aol"];
    $group_types = ["all_yahoo", "all_comcast", "all_hotmail", "all_gmail", "all_aol"];
    if (!in_array($isp_type, $isp_types) || !in_array($group_type, $group_types)) {
        echo json_encode(['status' => 'false', 'error' => 'Invalid Type']);
        exit();
    }
    $add_isp = $isp_map[$isp_type];
    $data_file_name = "{$add_isp}_{$list_id}{$sub_seg_id}{$seg_id}";
    $suppression_file_name = "{$add_isp}_{$list_id}{$sub_seg_id}{$seg_id}_obc{$add_f}{$add_eb}_{$offer_suppression_id}";
    $insertResult = pg_query_params(
        $con,
        "INSERT INTO tbl_download_toll (isp_type, group_type, list_id, sub_seg_id, seg_id, fbl_type, offer_suppression_id, esp_bounce_type, unique_type, dnd_type, data_file_name, suppression_file_name, status, create_by, c_date) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15) RETURNING download_id",
        [$isp_type, $group_type, $list_id, $sub_seg_id, $seg_id, $fbl_type, $offer_suppression_id, $esp_bounce_type, $unique_type, $dnd_type, $data_file_name, $suppression_file_name, 'Pending', $create_by, $c_date]
    );
    if ($insertResult) {
        $download_id = pg_fetch_result($insertResult, 0, 'download_id');
        $sqlDataFileFilter = "SELECT COUNT(email_details_id) AS table_count FROM tbl_email_details WHERE delete_status != '1' AND ds = $1 AND isp_type = $2";
        $queryDataFileFilter = pg_query_params($con, $sqlDataFileFilter, array("{$list_id}{$sub_seg_id}{$seg_id}", $isp_type));
        $sqlOfferSuppFilter = "SELECT STRING_AGG(t2.mail_id::text, ',') AS hash_ids FROM tbl_suppression t1 LEFT JOIN tbl_offer_suppression_file t2 ON t1.suppression_id = t2.suppression_id WHERE t1.delete_status != '1' AND suppression_file_name ILIKE $1";
        $queryOfferSuppFilter = pg_query_params($con, $sqlOfferSuppFilter, array("{$offer_suppression_id}suppression%"));
        $rowOfferSuppFilter = pg_fetch_assoc($queryOfferSuppFilter);
        $dataOfferSuppFilterIds = array_flip(explode(',', $rowOfferSuppFilter['hash_ids']));

        $query_fbl = ($fbl_type == 'Yes') ? 'LEFT JOIN tbl_fbl_file t2 ON t1.emid = t2.emid' : '';
        $where_fbl = ($fbl_type == 'Yes') ? 'AND t2.emid IS NULL' : '';
        $select_fbl = ($fbl_type == 'Yes') ? ', COUNT(CASE WHEN t1.emid = t2.emid THEN 1 ELSE NULL END) AS fbl_count' : '';
        $query_esp = ($esp_bounce_type == 'Yes') ? 'LEFT JOIN tbl_esp_bounce_file t3 ON t1.email = t3.mail_id' : '';
        $where_esp = ($esp_bounce_type == 'Yes') ? 'AND t3.mail_id IS NULL' : '';
        $select_esp = ($fbl_type == 'Yes') ? ', COUNT(CASE WHEN t1.email = t3.mail_id THEN 1 ELSE NULL END) AS esp_count ' : '';
        $sqlFblEspSuppFilter = "SELECT STRING_AGG(email::text, ',') AS email_ids, STRING_AGG(email_details_id::text, ',') AS table_ids  $select_fbl $select_esp FROM tbl_email_details t1 $query_fbl $query_esp WHERE t1.delete_status != '1' AND t1.ds = $1 AND t1.isp_type = $2 $where_fbl $where_esp";

        $queryFblEspSuppFilter = pg_query_params($con, $sqlFblEspSuppFilter, array("{$list_id}{$sub_seg_id}{$seg_id}", $isp_type));
        $rowFblEspSuppFilter = pg_fetch_assoc($queryFblEspSuppFilter);
        $dataFblEspSuppFilterIds = explode(',', $rowFblEspSuppFilter['email_ids']);
        $dataFblEspSuppFilterTableIds = explode(',', $rowFblEspSuppFilter['table_ids']);
        $fblSuppCount = 0;
        $espSuppCount = 0;
        if (isset($rowFblEspSuppFilter['fbl_count'])) {
            $fblSuppCount = $rowFblEspSuppFilter['fbl_count'];
        }
        if (isset($rowFblEspSuppFilter['esp_count'])) {
            $espSuppCount = $rowFblEspSuppFilter['esp_count'];
        }

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
        if ($queryDataFileFilter) {
            $rowDataFileFilter = pg_fetch_assoc($queryDataFileFilter);
            $dataFileFilterCount = $rowDataFileFilter['table_count'];
            $suppFileFilterCount = count($no_match_table_ids);
            $suppFileFilterIds = implode(',', $no_match_table_ids);
            $offerSuppCount = count($match_table_ids);
            $end_time = microtime(true);
            $execution_time = $end_time - $start_time;
            $updateDownloadToll = pg_query_params($con, "UPDATE tbl_download_toll SET data_file_count=$1, suppression_file_count=$2, suppression_file_ids=$3, execution_time=$4, offer_suppression_count=$5, fbl_suppression_count=$6, esp_suppression_count=$7 WHERE download_id=$8", array($dataFileFilterCount, $suppFileFilterCount, $suppFileFilterIds, $execution_time, $offerSuppCount, $fblSuppCount, $espSuppCount, $download_id));
            if ($updateDownloadToll) {
                $data = array('status' => 'true', 'id' => $download_id, 'download' => $suppFileFilterIds, 'filename' => $suppression_file_name, 'unique' => $_REQUEST['unique_type']);
            } else {
                $data = array('status' => 'false', 'error' => pg_last_error($con));
            }
        } else {
            $data = array('status' => 'false', 'error' => pg_last_error($con));
        }
    } else {
        $data = array('status' => 'false', 'error' => pg_last_error($con));
    }
    echo json_encode($data);
    exit();
}

if ($type == 'getDownloadToll') {
    $output = array();
    $sql = "SELECT * FROM tbl_download_toll ";
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
    $download_toll_id = $_POST['id'];
    $download_ids = $_POST['download_ids'];
    $filename = $_POST['filename'];
    $unique = $_POST['unique'];
    $if_unique = ($unique == 'Yes') ? 'DISTINCT ON (email)' : '';
    $sql = "SELECT $if_unique emid, email, ds, isp_type, edate, e_ip, fname, lname, suburl, subdate, click, open, flag FROM tbl_email_details WHERE delete_status != '1' AND email_details_id IN ($download_ids) ORDER BY email, emid";
    $result = pg_query($con, $sql);

    if (!$result) {
        $data = array('status' => 'false', 'error' => pg_last_error($con));
    } else {
        $file_path = '../' . $filename . '.txt';
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

            $update = pg_query_params($con, "UPDATE tbl_download_toll SET status=$1 WHERE download_id=$2", array('Completed', $download_toll_id));

            if ($update) {
                $data = array('status' => 'true');
            } else {
                $data = array('status' => 'false', 'error' => pg_last_error($con));
            }
        } else {
            $data = array('status' => 'false', 'error' => 'Cannot open file for writing');
        }
    }
    echo json_encode($data);
    exit();
}
