<?php include('header.php'); ?>
<style type="text/css">
    .custom-file-button input[type=file] {
        margin-left: -2px !important;
    }

    .custom-file-button input[type=file]::-webkit-file-upload-button {
        display: none;
    }

    .custom-file-button input[type=file]::file-selector-button {
        display: none;
    }

    .custom-file-button:hover label {
        background-color: #dde0e3;
        cursor: pointer;
    }

    .form-group label {
        display: block;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        width: 100%;
    }

    .form-group {
        margin-bottom: 5px;
    }
</style>
<!-- begin::main content -->
<main class="main-content">

    <div class="container-fluid">

        <!-- begin::page header -->
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <h4>Email Form</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-t-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Email Form</li>
                </ol>
            </nav>
        </div>
        <!-- end::page header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Email Information</h5>
                        <form class="" id="emailForm">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Email Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="emid" name="emid" placeholder="Enter Email id">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User Mail Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter User Mail Id">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Data Stat Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="ds" name="ds" placeholder="Enter Data Stat Id">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>ISP Type
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="isp_type" name="isp_type">
                                            <option value="yahoo">Yahoo</option>
                                            <option value="comcast">Comcast</option>
                                            <option value="hotmail">Hotmail</option>
                                            <option value="gmail">Gmail</option>
                                            <option value="aol">Aol</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Entry Date of db</label>
                                        <input type="text" name="single-date-picker" id="edate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>When user subscribed in internet ip</label>
                                        <input type="text" class="form-control" id="e_ip" name="e_ip" placeholder="Enter internet ip">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First Name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter Last Name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User subscribed website
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="suburl" name="suburl" placeholder="Enter User subscribed website">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>When user subscribed in website</label>
                                        <input type="text" name="single-date-picker" id="subdate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User clicked the mail</label>
                                        <input type="text" name="single-date-picker" id="click" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>User opened the mail</label>
                                        <input type="text" name="single-date-picker" id="open" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Flag
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="flag" name="flag">
                                            <option value="Active">Active</option>
                                            <option value="Bounce">Bounce</option>
                                            <option value="Complaint">Complaint</option>
                                            <option value="Unsubscribe">Unsubscribe</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="edit_id" name="edit_id">
                            <input type="hidden" name="trid" id="trid">
                            <button type="submit" id="addEmail" class="btn btn-primary">Submit</button>
                            <button type="submit" id="updateEmail" class="btn btn-primary" style="display: none;">Update</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">Email Details List</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="input-group custom-file-button">
                                        <button type="button" id="uploadEmail" class="btn btn-primary">Upload</button>
                                        <input type="file" class="form-control" id="uploadEmailFile" name="uploadEmailFile">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table id="emailTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Email Id</th>
                                    <th>User Mail Id</th>
                                    <th>Data Stat Id</th>
                                    <th>Last Update</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#emailTable').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': true,
            'processing': true,
            'paging': true,
            'responsive': true,
            'order': [],
            'ajax': {
                'url': 'phpservices/email_services.php',
                'type': 'POST',
                'data': {
                    'services_type': "getEmail"
                },
            },
            "columnDefs": [{
                "targets": "_all", // Apply to all columns
                "orderable": false
            }]
        });
        $('#emailTable').css('width', '100%');
    });

    $(document).ready(function() {
        var emailForm = $("#emailForm");

        emailForm.validate({
            rules: {
                emid: "required",
                email: {
                    required: true,
                    email: true
                },
                ds: "required",
                isp_type: "required",
                suburl: "required",
                flag: "required",
            },
            messages: {
                emid: '<small class="form-text text-danger">Please enter the email id.</small>',
                email: {
                    required: '<small class="form-text text-danger">Please enter the user mail id.</small>',
                    email: '<small class="form-text text-danger">Please enter the vaild user mail id.</small>'
                },
                ds: '<small class="form-text text-danger">Please enter the data stat id.</small>',
                isp_type: '<small class="form-text text-danger">Please enter the isp type.</small>',
                suburl: '<small class="form-text text-danger">Please enter the user subscribed website.</small>',
                flag: '<small class="form-text text-danger">Please enter the flag.</small>',
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                var edit_id = $('#edit_id').val();
                var trid = $('#trid').val();

                if (edit_id === '') {
                    formData.append('services_type', 'addEmail');
                } else {
                    formData.append('services_type', 'updateEmail');
                }
                formData.append('edate', $('#edate').val());
                formData.append('subdate', $('#subdate').val());
                formData.append('click', $('#click').val());
                formData.append('open', $('#open').val());
                $.ajax({
                    url: "phpservices/email_services.php",
                    type: "post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'true') {
                            // Reset the form
                            form.reset();
                            if (edit_id == '') {
                                var table = $('#emailTable').DataTable(); // Declare mytable variable
                                table.draw();
                                Swal.fire({
                                    title: 'You are Added successfully',
                                    icon: 'success',
                                });
                            } else {
                                var table = $('#emailTable').DataTable(); // Declare table variable

                                var lastUpdate = '<span class="badge badge-warning text-white">Edited by <?php echo $user_name; ?> on<br><?php echo $e_date; ?></span>';

                                var button = '';
                                button += '<td><button type="button" data-id="' + edit_id + '" data-value="' + trid + '" class="btn btn-warning m-r-5 mb-1 editBtnEmail"><i class="icon ti-pencil-alt" style="font-size: 16px!important;color: white!important;"></i></button>';
                                <?php if ($menu_emp['master_del'] == '1') { ?>
                                    button += '<button type="button" data-id="' + edit_id + '" data-value="' + trid + '" class="btn btn-danger m-r-5 mb-1 deleteBtnEmail"><i class="icon ti-trash" style="font-size: 16px!important;color: white!important;"></i></button></td>';
                                <?php } ?>
                                button += '</td>';

                                var row = table.row("#" + trid);
                                row.data([trid, formData.get('emid'), formData.get('email'), formData.get('ds'), lastUpdate, button]);
                                Swal.fire({
                                    title: 'You are Update successfully',
                                    icon: 'success',
                                });
                            }
                            $("#addEmail").show();
                            $("#updateEmail").hide();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                text: json.error || 'An error occurred',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: error
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.deleteBtnEmail', function(event) {
        var table = $('#emailTable').DataTable();
        event.preventDefault();
        var id = $(this).data('id');
        var trid = $(this).data('value');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "phpservices/email_services.php",
                    data: {
                        d_id: id,
                        services_type: "deleteEmail"
                    },
                    type: "post",
                    success: function(data) {
                        var json = JSON.parse(data);
                        status = json.status;
                        if (status == 'true') {
                            $("#" + trid).closest('tr').remove();
                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        } else {
                            alert('Failed');
                            return;
                        }
                    }
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        });
    });

    $(document).on('click', '.editBtnEmail', function(event) {
        var table = $('#emailTable').DataTable();
        var trid = $(this).data('value');
        var id = $(this).data('id');
        $("#addEmail").hide();
        $("#updateEmail").show();
        window.scrollTo(0, 0);
        $.ajax({
            url: "phpservices/email_services.php",
            data: {
                id: id,
                services_type: "editEmail"
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                $('#emid').val(json.emid);
                $('#email').val(json.email);
                $('#ds').val(json.ds);
                $('#isp_type').val(json.isp_type);
                $('#edate').val(moment(json.edate).format('DD-MM-YYYY'));
                $('#e_ip').val(json.e_ip);
                $('#fname').val(json.fname);
                $('#lname').val(json.lname);
                $('#suburl').val(json.suburl);
                $('#subdate').val(moment(json.subdate).format('DD-MM-YYYY'));
                $('#click').val(moment(json.click).format('DD-MM-YYYY'));
                $('#open').val(moment(json.open).format('DD-MM-YYYY'));
                var flag = json.flag;
                $("#flag").select2().val(flag).trigger("change");
                $('#edit_id').val(id);
                $('#trid').val(trid);
            }
        })
    });

    $(document).on('click', '#uploadEmail', function(event) {
        const uploadEmailFile = $('#uploadEmailFile')[0].files[0];
        if (uploadEmailFile) {
            let formData = new FormData();
            formData.append('file', uploadEmailFile);
            formData.append('services_type', 'uploadEmail');
            $(".page-loader").show();
            $.ajax({
                url: "phpservices/email_services.php",
                data: formData,
                type: "post",
                processData: false,
                contentType: false,
                success: function(data) {
                    var json = JSON.parse(data);
                    status = json.status;
                    $(".page-loader").hide();
                    if (status == 'true') {
                        $('#uploadEmailFile').val('');
                        var table = $('#emailTable').DataTable();
                        table.draw();
                        Swal.fire({
                            title: 'You are Added successfully',
                            icon: 'success',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: json.error || 'An error occurred',
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Upload file empty!',
            });
        }
    });
</script>