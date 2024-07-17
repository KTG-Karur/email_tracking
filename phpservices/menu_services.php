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
            <li>
                <a href="#"><i class="icon fa fa-hashtag"></i> <span>Master</span> </a>
                <ul>
                    <li><a href="email_details.php">Email DataBasic</a></li>
                    <li><a href="suppression_details.php">Suppression Details</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="icon fa fa-wrench"></i> <span>Tools</span> </a>
                <ul>
                    <li><a href="download_tool_details.php">Download Tool</a></li>
                    <li><a href="download_tool_list.php">Download Tool List</a></li>
                </ul>
            </li>
        <?php } ?>
    </ul>
<?php
}
?>