<!-- begin::global scripts -->
<script src="vendors/bundle.js"></script>
<!-- end::global scripts -->

<!-- begin::charts -->
<script src="vendors/charts/chartjs/chart.min.js"></script>
<script src="vendors/charts/peity/jquery.peity.min.js"></script>
<script src="js/examples/charts/chartjs.js"></script>
<script src="js/examples/charts/peity.js"></script>
<!-- end::charts -->

<!-- begin::daterangepicker -->
<script src="vendors/datepicker/daterangepicker.js"></script>
<!-- end::daterangepicker -->

<!-- begin::dashboard -->
<script src="js/examples/dashboard.js"></script>
<!-- end::dashboard -->

<!-- begin::vamp -->
<script src="vendors/vmap/jquery.vmap.min.js"></script>
<script src="vendors/vmap/maps/jquery.vmap.usa.js"></script>
<script src="js/examples/vmap.js"></script>
<!-- end::vamp -->

<!-- begin::dataTable -->
<script src="vendors/dataTable/jquery.dataTables.min.js"></script>
<script src="vendors/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="vendors/dataTable/dataTables.responsive.min.js"></script>
<script src="js/examples/datatable.js"></script>
<!-- end::dataTable -->

<!-- begin::select2 -->
<script src="vendors/select2/js/select2.min.js"></script>
<script src="js/examples/select2.js"></script>
<!-- end::select2 -->

<!-- begin::custom scripts -->
<!-- <script src="js/custom.js"></script> -->
<script src="js/app.min.js"></script>
<!-- end::custom scripts -->

<!-- begin::datepicker -->
<script src="vendors/datepicker/daterangepicker.js"></script>
<script src="js/examples/datepicker.js"></script>
<!-- end::datepicker -->

<!-- begin::input mask -->
<script src="vendors/input-mask/jquery.mask.js"></script>
<script src="js/examples/input-mask.js"></script>
<!-- end::input mask -->

<!-- begin::input mask -->
<script src="vendors/tagsinput/bootstrap-tagsinput.js"></script>
<script src="js/examples/tagsinput.js"></script>
<!-- end::input mask -->

<!----sweetalert2----->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.all.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.31/sweetalert2.js"></script>
<!----sweetalert2----->

<!---Form Validate--->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!---Form Validate--->
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "phpservices/session_services.php",
            cache: false,
            data: {
                services_type: "session_checker",
            },
            success: function(data) {
                var json = JSON.parse(data);
                var status = json.status;
                if (status == 'false') {
                    window.location.href = "index.php";
                }
            }
        })
    });

    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "phpservices/menu_services.php",
            cache: false,
            data: {
                services_type: "menu_list",
            },
            success: function(result) {
                $("#menu_page").html(result);
                if (result) {
                    var href = document.location.href;
                    var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
                    $('a[href="' + lastPathSegment + '"]').parents('li').addClass('open');
                }
            }
        })
    });
</script>