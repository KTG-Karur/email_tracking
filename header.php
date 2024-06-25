<?php
error_reporting(0);
include('./phpservices/config.php'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <meta name="description" content="">
    <meta name="keywords" content="Finance - finance">
    <meta name="author" content="Knock the globe Technologies, Karur, Tamilnadu, Web designing, Web development, UI designing, App development, Textile Software, SEO.">

    <link rel="shortcut icon" href="media/image/logo.png" id="fav-shortcut" type="image/x-icon">
    <link rel="icon" href="media/image/logo.png" id="fav-icon" type="image/x-icon">

    <!-- begin::global styles -->
    <link rel="stylesheet" href="vendors/bundle.css" type="text/css">
    <!-- end::global styles -->

    <!-- begin::datepicker -->
    <link rel="stylesheet" href="vendors/datepicker/daterangepicker.css">
    <!-- begin::datepicker -->

    <!-- begin::select2 -->
    <link rel="stylesheet" href="vendors/select2/css/select2.min.css" type="text/css">
    <!-- end::select2 -->

    <!-- begin::vmap -->
    <link rel="stylesheet" href="vendors/vmap/jqvmap.min.css">
    <!-- begin::vmap -->

    <!-- begin::dataTable -->
    <link rel="stylesheet" href="vendors/dataTable/responsive.bootstrap.min.css" type="text/css">
    <!-- end::dataTable -->

    <!-- begin::custom styles -->
    <link rel="stylesheet" href="css/app.min.css" type="text/css">
    <link rel="stylesheet" href="css/custom.css" type="text/css">
    <link rel="stylesheet" href="css/themify-icons.css" type="text/css">
    <!-- end::custom styles -->

    <!-- begin::font-awesome styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- end::font-awesome styles -->

    <!-- begin::datepicker -->
    <link rel="stylesheet" href="vendors/datepicker/daterangepicker.css">
    <!-- end::datepicker -->

    <!-- begin::tagsinput -->
    <link rel="stylesheet" href="vendors/tagsinput/bootstrap-tagsinput.css" type="text/css">
    <!-- end::tagsinput -->

    <!----sweetalert2----->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.min.css">
    <!----sweetalert2----->

</head>

<body>

    <!-- begin::page loader-->
    <div class="page-loader">
        <div class="spinner-border"></div>
        <span>Loading ...</span>
    </div>
    <!-- end::page loader -->

    <!-- begin::side menu -->
    <div class="side-menu">
        <div class='side-menu-body' id="menu_page">
            <!--- ajax page---->
        </div>
    </div>
    <!-- end::side menu -->

    <!-- begin::navbar -->
    <nav class="navbar">
        <div class="container-fluid">

            <div class="header-logo">
                <a href="#">
                    <span class="logo-text d-none d-lg-block">Admin Dashboard</span>
                </a>
            </div>

            <div class="header-body">
                <ul class="navbar-nav">
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown">
                            <figure class="avatar avatar-sm avatar-state-success">
                                <img class="rounded-circle" src="media/image/avatar.jpg" alt="...">
                            </figure>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" data-sidebar-target="#settings" class="sidebar-open dropdown-item">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="text-danger dropdown-item">Logout</a>
                        </div>
                    </li>
                    <li class="nav-item d-lg-none d-sm-block">
                        <a href="#" class="nav-link side-menu-open">
                            <i class="ti-menu"></i>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <!-- end::navbar -->
    <style type="text/css">
        ul:not(.list-unstyled) li a .icon {
            margin-right: 0px;
        }

        nav.navbar .header-logo a .logo-text {
            font-size: 15px;
        }

        .add-btu {
            background: white !important;
            border-color: #8360c3 !important;
        }

        .add-btu span {
            color: #8360c3;
            font-weight: bold;
        }

        .add-btu .add-icon {
            color: #8360c3;
            font-weight: bold;
        }

        .add-btu:hover {
            background: #8360c3 !important;
        }

        .add-btu:hover span {
            color: white;
            font-weight: bold;
        }

        .add-btu:hover .add-icon {
            color: white;
            font-weight: bold;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .btn-title-text {
            width: 100%;
            padding: 15px 15px;
            font-weight: 800;
            font-size: 22px;
        }

        .text-end {
            text-align: end;
        }

        body nav.navbar {
            background: #2ebf91;
        }
    </style>