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
                        <table id="downloadToolTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
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
    var downloadToolTable;
    $(document).ready(function() {
        downloadToolTable = $('#downloadToolTable').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[7]);
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
                "targets": "_all",
                "orderable": false
            }]
        });

        $('#downloadToolTable').css('width', '100%');

        setInterval(function() {
            var table = $('#downloadToolTable').DataTable();
            table.draw();
        }, 5000);
        
        // function downloadCompletedBtnEmail() {
        //     $.ajax({
        //         url: "phpservices/downloadTool_services.php",
        //         data: {
        //             services_type: "downloadTool"
        //         },
        //         type: 'POST',
        //         success: function(data) {
        //             try {
        //                 var json = JSON.parse(data);
        //                 var status = json.status;
        //                 if (status === 'true') {
        //                     var table = $('#downloadToolTable').DataTable();
        //                     table.draw();
        //                 } else if (status === 'false') {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Download failed',
        //                         text: json.error || 'An error occurred',
        //                     });
        //                 }
        //             } catch (error) {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Parsing error',
        //                     text: 'An error occurred while parsing server response.',
        //                 });
        //             }
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'AJAX error',
        //                 text: 'An error occurred while communicating with the server.',
        //             });
        //         },
        //         complete: function() {
        //             setTimeout(downloadCompletedBtnEmail, 5000);
        //         }
        //     });
        // }
        // downloadCompletedBtnEmail();
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