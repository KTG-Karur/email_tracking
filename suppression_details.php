<?php include('header.php'); ?>

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
                                        <select class="js-example-basic-single" id="flag" name="flag">
                                            <option value="Active">Offer suppression file</option>
                                            <option value="Bounce">Bounce</option>
                                            <option value="Complaint">Complaint</option>
                                            <option value="Unsubscribe">Unsubscribe</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="edit_id" name="edit_id">
                            <input type="hidden" name="trid" id="trid">
                            <button type="submit" id="addSuppression" class="btn btn-primary">Submit</button>
                            <button type="submit" id="updateSuppression" class="btn btn-primary" style="display: none;">Update</button>
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
                                    <th>Offer suppression file</th>
                                    <th>Bounce file</th>
                                    <th>Complaint file</th>
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
                offer_sup_file: "required",
                bounce_file: "required",
                complaint_file: "required",
                fbl_file: "required",
                opt_out_file: "required",
                unsub_file: "required",
                esp_bounce_file: "required",
            },
            messages: {
                offer_sup_file: '<small class="form-text text-danger">Please enter the offer suppression file.</small>',
                bounce_file: '<small class="form-text text-danger">Please enter the bounce file.</small>',
                complaint_file: '<small class="form-text text-danger">Please enter the complaint file.</small>',
                fbl_file: '<small class="form-text text-danger">Please enter the fbl file.</small>',
                opt_out_file: '<small class="form-text text-danger">Please enter the opt out file.</small>',
                unsub_file: '<small class="form-text text-danger">Please enter the unsubscribe file.</small>',
                esp_bounce_file: '<small class="form-text text-danger">Please enter the user esp bounce file.</small>',
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                var edit_id = $('#edit_id').val();
                var trid = $('#trid').val();

                if (edit_id === '') {
                    formData.append('services_type', 'addSuppression');
                } else {
                    formData.append('services_type', 'updateSuppression');
                }
                formData.append('edate', $('#edate').val());
                formData.append('subdate', $('#subdate').val());
                $.ajax({
                    url: "phpservices/suppression_services.php",
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
                                var table = $('#suppressionTable').DataTable(); // Declare mytable variable
                                table.draw();
                                Swal.fire({
                                    title: 'You are Added successfully',
                                    icon: 'success',
                                });
                            } else {
                                var table = $('#suppressionTable').DataTable(); // Declare table variable

                                var lastUpdate = '<span class="badge badge-warning text-white">Edited by <?php echo $user_name; ?> on<br><?php echo $e_date; ?></span>';

                                var button = '';
                                button += '<td><button type="button" data-id="' + edit_id + '" data-value="' + trid + '" class="btn btn-warning m-r-5 mb-1 editBtnSuppression"><i class="icon ti-pencil-alt" style="font-size: 16px!important;color: white!important;"></i></button>';
                                <?php if ($menu_emp['master_del'] == '1') { ?>
                                    button += '<button type="button" data-id="' + edit_id + '" data-value="' + trid + '" class="btn btn-danger m-r-5 mb-1 deleteBtnSuppression"><i class="icon ti-trash" style="font-size: 16px!important;color: white!important;"></i></button></td>';
                                <?php } ?>
                                button += '</td>';

                                var row = table.row("#" + trid);
                                row.data([trid, formData.get('offer_sup_file'), formData.get('bounce_file'), formData.get('complaint_file'), lastUpdate, button]);
                                Swal.fire({
                                    title: 'You are Update successfully',
                                    icon: 'success',
                                });
                            }
                            $("#addSuppression").show();
                            $("#updateSuppression").hide();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: status
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

    $(document).on('click', '.editBtnSuppression', function(event) {
        var table = $('#suppressionTable').DataTable();
        var trid = $(this).data('value');
        var id = $(this).data('id');
        $("#addSuppression").hide();
        $("#updateSuppression").show();
        window.scrollTo(0, 0);
        $.ajax({
            url: "phpservices/suppression_services.php",
            data: {
                id: id,
                services_type: "editSuppression"
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                $('#offer_sup_file').val(json.offer_sup_file);
                $('#bounce_file').val(json.bounce_file);
                $('#complaint_file').val(json.complaint_file);
                $('#fbl_file').val(json.fbl_file);
                $('#opt_out_file').val(json.opt_out_file);
                $('#unsub_file').val(json.unsub_file);
                $('#esp_bounce_file').val(json.esp_bounce_file);
                $('#edit_id').val(id);
                $('#trid').val(trid);
            }
        })
    });
</script>