<?php 
error_reporting(0);
include('config.php'); 
session_start();

$type=$_REQUEST['services_type'];

if($type=='session_checker')
{
if ($_SESSION['type']=='' && $_SESSION['memid']=='') 
{
 $data = array( 'status'=>'false',);
 echo json_encode($data);
} else {
 $data = array( 'status'=>'true',);
 echo json_encode($data);
}
}

?>