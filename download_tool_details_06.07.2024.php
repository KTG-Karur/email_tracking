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

    .col-md-1,
    .col-md-2 {
        padding-right: 5px;
        padding-left: 5px;
    }
</style>
<!-- begin::main content -->
<main class="main-content">

    <div class="container-fluid">

        <!-- begin::page header -->
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <h4>Download Tool</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-t-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Download Tool</li>
                </ol>
            </nav>
        </div>
        <!-- end::page header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Download Tool List</h5>
                        <form class="" id="downloadToolForm">
                            <div class="row">
                                <div class="col-md-1">
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
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Group
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="group_type" name="group_type">
                                            <option value="all_yahoo">All Yahoo</option>
                                            <option value="all_comcast">All Comcast</option>
                                            <option value="all_hotmail">All Hotmail</option>
                                            <option value="all_gmail">All Gmail</option>
                                            <option value="all_aol">All Aol</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>List Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="list_id" name="list_id" placeholder="Enter List Id">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Sub Seg Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="sub_seg_id" name="sub_seg_id" placeholder="Enter Sub seg id">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Seg Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="seg_id" name="seg_id" placeholder="Enter Seg Id">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>FBL
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="fbl_type" name="fbl_type">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Offer Suppression Id
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="offer_suppression_ids" name="offer_suppression_ids" placeholder="Enter Offer Suppression Id">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>ESP Bounce
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="esp_bounce_type" name="esp_bounce_type">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Unique
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="unique_type" name="unique_type">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>DND
                                            <span style="color:red;font-size: 20px;font-weight: bold;">*</span>
                                        </label>
                                        <select class="js-example-basic-single" id="dnd_type" name="dnd_type">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="margin-top: 35px;">
                                    <button type="submit" id="addDownloadTool" class="btn btn-primary">Download</button>
                                </div>
                            </div>
                        </form>
                        <table id="downloadToolTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>FBL</th>
                                    <th>Created-ECode</th>
                                    <th>Data File</th>
                                    <th>Suppression File</th>
                                    <th>Status</th>
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

<!------------------------Start Download Status Popup------------------------------>
<div class="modal fade" id="downloadStatusPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lx">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Download Status Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>Offer Suppression</td>
                        <td id="offer_suppression_count">0</td>
                    </tr>
                    <tr>
                        <td>Bounce Suppression</td>
                        <td id="bounce_suppression_count">1145</td>
                    </tr>
                    <tr>
                        <td>Complaints(SP) Suppression</td>
                        <td id="complaint_suppression_count">0</td>
                    </tr>
                    <tr>
                        <td>FBL Suppression</td>
                        <td id="fbl_suppression_count">138</td>
                    </tr>
                    <tr>
                        <td>Total Execution Time</td>
                        <td id="execution_time">4.6 Mins</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>
<!------------------------END Download Status Popup------------------------------>

<?php include('footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#downloadToolTable').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': true,
            'processing': true,
            'paging': true,
            'responsive': true,
            'order': [],
            'ajax': {
                'url': 'phpservices/downloadTool_services.php',
                'type': 'POST',
                'data': {
                    'services_type': "getDownloadTool"
                },
            },
            "columnDefs": [{
                "targets": "_all", // Apply to all columns
                "orderable": false
            }]
        });
        $('#downloadToolTable').css('width', '100%');
    });

    $(document).ready(function() {
        var downloadToolForm = $("#downloadToolForm");

        downloadToolForm.validate({
            rules: {
                isp_type: "required",
                group_type: "required",
                list_id: "required",
                sub_seg_id: "required",
                seg_id: "required",
                fbl_type: "required",
                offer_suppression_ids: "required",
                esp_bounce_type: "required",
                unique_type: "required",
                dnd_type: "required",
            },
            messages: {
                isp_type: '<small class="form-text text-danger">Please select the ISP type.</small>',
                group_type: '<small class="form-text text-danger">Please select the group.</small>',
                list_id: '<small class="form-text text-danger">Please enter the list id.</small>',
                sub_seg_id: '<small class="form-text text-danger">Please enter the sub seg id.</small>',
                seg_id: '<small class="form-text text-danger">Please enter the seg id.</small>',
                fbl_type: '<small class="form-text text-danger">Please select the FBL.</small>',
                offer_suppression_ids: '<small class="form-text text-danger">Please enter the offer suppression id.</small>',
                esp_bounce_type: '<small class="form-text text-danger">Please select the ESP bounce.</small>',
                unique_type: '<small class="form-text text-danger">Please select the unique.</small>',
                dnd_type: '<small class="form-text text-danger">Please select the DND.</small>',
            },
            submitHandler: function(form) {
                var offerSuppressionIds = $("#offer_suppression_ids").val().split(',');
                for (let i = 0; i < offerSuppressionIds.length; ++i) {
                    var formData = new FormData(form);
                    formData.append('services_type', 'addDownloadTool');
                    formData.append('offer_suppression_id', offerSuppressionIds[i].trim());

                    $.ajax({
                        url: "phpservices/downloadTool_services.php",
                        type: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            var json = JSON.parse(data);
                            var status = json.status;
                            if (status == 'true') {
                                form.reset();
                                var table = $('#downloadToolTable').DataTable();
                                table.draw();
                                setTimeout(function() {
                                    $.ajax({
                                        url: "phpservices/downloadTool_services.php",
                                        data: {
                                            id: json.id,
                                            services_type: "downloadTool"
                                        },
                                        type: 'post',
                                        success: function(data) {
                                            var json = JSON.parse(data);
                                            var status = json.status;
                                            if (status == 'true') {
                                                var table = $('#downloadToolTable').DataTable();
                                                table.draw();
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Download failed',
                                                    text: json.error || 'An error occurred',
                                                });
                                            }
                                        }
                                    });
                                }, 200);
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
            }
        });
    });

    $(document).on('click', '.downloadStatusBtnEmail', function(event) {
        var table = $('#downloadToolTable').DataTable();
        var trid = $(this).data('value');
        var rowData = $(this).data('row');

        var totalSeconds = Number(rowData.execution_time);
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        $('#execution_time').text(hours + ':' + minutes + ':' + seconds.toFixed(2));
        $('#offer_suppression_count').text(rowData.offer_suppression_count);
        $('#bounce_suppression_count').text(rowData.bounce_suppression_count);
        $('#complaint_suppression_count').text(rowData.complaint_suppression_count);
        $('#fbl_suppression_count').text(rowData.fbl_suppression_count);
    });
</script>