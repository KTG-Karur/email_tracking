<?php
error_reporting(0);
include('config.php');

$type = $_REQUEST['services_type'];

if ($type == 'menu_list') {
?>
    <ul>
        <li><a href="dashboard.php"><i class="icon fa fa-home"></i><span>Dashboards</span> </a></li>
        <li class="side-menu-divider">Menu</li>
        <?php if ($menu_emp['master_ins'] == '1' || $menu_emp['master_upd'] == '1' || $menu_emp['master_del'] == '1') { ?>
            <li><a href="email_details.php"><i class="icon fa fa-envelope"></i><span>Email Details</span></a></li>
            <li><a href="suppression_details.php"><i class="icon fa fa-envelope"></i><span>Suppression Details</span></a></li>
            <li><a href="download_toll_details.php"><i class="icon fa fa-envelope"></i><span>Download Toll</span></a></li>
        <?php } ?>
    </ul>
<?php
}
?>