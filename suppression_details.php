<?php include('header.php'); ?>
<style type="text/css">
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
            <h4>Suppression Form</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-t-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Suppression Form</li>
                </ol>
            </nav>
        </div>
        <!-- end::page header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suppression Information</h5>
                        <form class="" id="suppressionForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Suppression Type
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="suppression_type" name="suppression_type">
                                            <option value="Offer suppression file">Offer suppression file</option>
                                            <option value="Bounce file">Bounce file</option>
                                            <option value="Complaint file">Complaint file</option>
                                            <option value="FBL file">FBL file</option>
                                            <option value="Opt Out file">Opt Out file</option>
                                            <option value="Unsubscribe file">Unsubscribe file</option>
                                            <option value="Esp Bounce file">Esp Bounce file</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Suppression file
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="file" class="form-control" id="uploadEmailFile" name="uploadEmailFile">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="addSuppression" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Suppression Details List</h5>
                        <table id="suppressionTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Suppression Type</th>
                                    <th>Suppression File Name</th>
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
        $('#suppressionTable').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': true,
            'processing': true,
            'paging': true,
            'responsive': true,
            'order': [],
            'ajax': {
                'url': 'phpservices/suppression_services.php',
                'type': 'POST',
                'data': {
                    'services_type': "getSuppression"
                },
            },
            "columnDefs": [{
                "targets": "_all", // Apply to all columns
                "orderable": false
            }]
        });
        $('#suppressionTable').css('width', '100%');
    });

    $(document).ready(function() {
        var suppressionForm = $("#suppressionForm");

        suppressionForm.validate({
            rules: {
                suppression_type: "required",
                uploadEmailFile: "required",
            },
            messages: {
                suppression_type: '<small class="form-text text-danger">Please select the suppression type.</small>',
                uploadEmailFile: '<small class="form-text text-danger">Please upload the suppression file.</small>',
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                const uploadEmailFile = $('#uploadEmailFile')[0].files[0];
                formData.append('services_type', 'addSuppression');
                formData.append('file', uploadEmailFile);
                $(".page-loader").show();
                $.ajax({
                    url: "phpservices/suppression_services.php",
                    type: "post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        $(".page-loader").hide();
                        if (status == 'true') {
                            // Reset the form
                            form.reset();
                            var table = $('#suppressionTable').DataTable(); // Declare mytable variable
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

    $(document).on('click', '.deleteBtnSuppression', function(event) {
        var table = $('#suppressionTable').DataTable();
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
                $(".page-loader").show();
                $.ajax({
                    url: "phpservices/suppression_services.php",
                    data: {
                        d_id: id,
                        services_type: "deleteSuppression"
                    },
                    type: "post",
                    success: function(data) {
                        var json = JSON.parse(data);
                        status = json.status;
                        $(".page-loader").hide();
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
</script>