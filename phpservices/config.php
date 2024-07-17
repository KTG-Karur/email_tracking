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


// $start_time = microtime(true);

// $sqlDowToolFilter = "SELECT * FROM tbl_download_tool WHERE status = $1 LIMIT 1";
// $queryDowToolFilter = pg_query_params($con, $sqlDowToolFilter, ['Pending']);

// if ($queryDowToolFilter && pg_num_rows($queryDowToolFilter) > 0) {
//     while ($rowDowToolFilter = pg_fetch_assoc($queryDowToolFilter)) {
//         $download_tool_id = $rowDowToolFilter['download_id'];
//         $list_id = $rowDowToolFilter['list_id'];
//         $sub_seg_id = $rowDowToolFilter['sub_seg_id'];
//         $seg_id = $rowDowToolFilter['seg_id'];
//         $isp_type = $rowDowToolFilter['isp_type'];
//         $offer_suppression_id = $rowDowToolFilter['offer_suppression_id'];
//         $fbl_type = $rowDowToolFilter['fbl_type'];
//         $esp_bounce_type = $rowDowToolFilter['esp_bounce_type'];
//         $suppression_file_name = $rowDowToolFilter['suppression_file_name'];
//         $unique_type = $rowDowToolFilter['unique_type'];

//         $sqlDataFileFilter = "SELECT COUNT(email_details_id) AS table_count FROM tbl_email_details WHERE delete_status != '1' AND ds = $1 AND isp_type = $2";
//         $queryDataFileFilter = pg_query_params($con, $sqlDataFileFilter, ["{$list_id}{$sub_seg_id}{$seg_id}", $isp_type]);

//         if ($queryDataFileFilter) {
//             $rowDataFileFilter = pg_fetch_assoc($queryDataFileFilter);
//             $dataFileFilterCount = $rowDataFileFilter['table_count'];

//             $sqlOfferSuppFilter = "SELECT STRING_AGG(t2.mail_id::text, ',') AS hash_ids FROM tbl_suppression t1 LEFT JOIN tbl_offer_suppression_file t2 ON t1.suppression_id = t2.suppression_id WHERE t1.delete_status != '1' AND suppression_file_name ILIKE $1";
//             $queryOfferSuppFilter = pg_query_params($con, $sqlOfferSuppFilter, ["{$offer_suppression_id}suppression%"]);

//             if ($queryOfferSuppFilter) {

//                 $rowOfferSuppFilter = pg_fetch_assoc($queryOfferSuppFilter);
//                 $dataOfferSuppFilterIds = array_flip(explode(',', $rowOfferSuppFilter['hash_ids']));

//                 $query_fbl = ($fbl_type == 'Yes') ? 'LEFT JOIN tbl_fbl_file t2 ON t1.emid = t2.emid' : '';
//                 $where_fbl = ($fbl_type == 'Yes') ? 'AND t2.emid IS NULL' : '';
//                 $select_fbl = ($fbl_type == 'Yes') ? ', COUNT(DISTINCT t2.emid) AS fbl_count' : '';

//                 $query_esp = ($esp_bounce_type == 'Yes') ? 'LEFT JOIN tbl_esp_bounce_file t3 ON t1.email = t3.mail_id' : '';
//                 $where_esp = ($esp_bounce_type == 'Yes') ? 'AND t3.mail_id IS NULL' : '';
//                 $select_esp = ($esp_bounce_type == 'Yes') ? ', COUNT(DISTINCT t3.mail_id) AS esp_count' : '';

//                 $sqlFblEspSuppFilter = "SELECT 
//                 STRING_AGG(DISTINCT CASE 
//                     WHEN 
//                         t4.mail_id IS NULL AND 
//                         t5.mail_id IS NULL
//                           $where_fbl
//                           $where_esp
//                     THEN t1.email 
//                     ELSE NULL 
//                 END, ',') AS email_ids,
//                 STRING_AGG(DISTINCT CASE 
//                     WHEN 
//                         t4.mail_id IS NULL AND 
//                         t5.mail_id IS NULL 
//                           $where_fbl
//                           $where_esp
//                     THEN t1.email_details_id::text 
//                     ELSE NULL 
//                 END, ',') AS table_ids, 
//                 COUNT(DISTINCT t4.mail_id) AS bounce_count,
//                 COUNT(DISTINCT t5.mail_id) AS complaint_count
//                 $select_fbl
//                 $select_esp
//             FROM 
//                 tbl_email_details t1 
//             LEFT JOIN tbl_bounce_file t4 ON t1.email = t4.mail_id
//             LEFT JOIN tbl_complaint_file t5 ON t1.email = t5.mail_id
//             $query_fbl
//             $query_esp
//             WHERE 
//                 t1.delete_status != '1' AND 
//                 t1.ds = $1 AND 
//                 t1.isp_type = $2";
//                 $queryFblEspSuppFilter = pg_query_params($con, $sqlFblEspSuppFilter, ["{$list_id}{$sub_seg_id}{$seg_id}", $isp_type]);

//                 if ($queryFblEspSuppFilter) {
//                     $rowFblEspSuppFilter = pg_fetch_assoc($queryFblEspSuppFilter);

//                     $dataFblEspSuppFilterIds = explode(',', $rowFblEspSuppFilter['email_ids']);
//                     $dataFblEspSuppFilterTableIds = explode(',', $rowFblEspSuppFilter['table_ids']);

//                     $fblSuppCount = $rowFblEspSuppFilter['fbl_count'] ?? 0;
//                     $espSuppCount = $rowFblEspSuppFilter['esp_count'] ?? 0;
//                     $bounceSuppCount = $rowFblEspSuppFilter['bounce_count'] ?? 0;
//                     $complaintSuppCount = $rowFblEspSuppFilter['complaint_count'] ?? 0;

//                     $no_match_table_ids = [];
//                     $match_table_ids = [];

//                     foreach ($dataFblEspSuppFilterIds as $index => $email) {
//                         $hashed_email = md5($email);
//                         if (!isset($dataOfferSuppFilterIds[$hashed_email])) {
//                             $no_match_table_ids[] = $dataFblEspSuppFilterTableIds[$index];
//                         } else {
//                             $match_table_ids[] = $dataOfferSuppFilterIds[$hashed_email];
//                         }
//                     }

//                     $suppFileFilterCount = count($no_match_table_ids);
//                     $suppFileFilterIds = implode(',', $no_match_table_ids);
//                     $offerSuppCount = count($match_table_ids);

//                     $if_unique = ($unique_type === 'Yes') ? 'DISTINCT ON (email)' : '';

//                     if (!empty($suppFileFilterIds)) {
//                         $sql = "SELECT $if_unique emid, email, ds, isp_type, edate, e_ip, fname, lname, suburl, subdate, click, open, flag 
//                 FROM tbl_email_details 
//                 WHERE delete_status != '1' AND email_details_id IN ($suppFileFilterIds) 
//                 ORDER BY email, emid";
//                         $result = pg_query($con, $sql);

//                         if ($result) {
//                             $file_path = '../' . $suppression_file_name . '.txt';
//                             $file = fopen($file_path, 'w');

//                             if ($file) {
//                                 $fileContent = '';
//                                 while ($row = pg_fetch_assoc($result)) {
//                                     $line = implode('|', [
//                                         $row['emid'],
//                                         $row['email'],
//                                         $row['ds'],
//                                         $row['isp_type'],
//                                         $row['edate'],
//                                         $row['e_ip'],
//                                         $row['fname'],
//                                         $row['lname'],
//                                         $row['suburl'],
//                                         $row['subdate'],
//                                         $row['click'],
//                                         $row['open'],
//                                         $row['flag'],
//                                     ]);
//                                     $fileContent .= $line . PHP_EOL;
//                                 }

//                                 fwrite($file, $fileContent);
//                                 fclose($file);

//                                 $end_time = microtime(true);
//                                 $execution_time = $end_time - $start_time;

//                                 $updateDownloadTool = pg_query_params(
//                                     $con,
//                                     "UPDATE tbl_download_tool 
//                                     SET data_file_count=$1, suppression_file_count=$2, execution_time=$3, offer_suppression_count=$4, 
//                                         fbl_suppression_count=$5, esp_suppression_count=$6, bounce_suppression_count=$7, complaint_suppression_count=$8, 
//                                         status=$9 
//                                     WHERE download_id=$10",
//                                     [
//                                         $dataFileFilterCount, $suppFileFilterCount, $execution_time, $offerSuppCount,
//                                         $fblSuppCount, $espSuppCount, $bounceSuppCount, $complaintSuppCount, 'Completed', $download_tool_id
//                                     ]
//                                 );
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     }
// }
