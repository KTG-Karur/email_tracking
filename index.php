<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>

    <!-- begin::global styles -->
    <link rel="stylesheet" href="vendors/bundle.css" type="text/css">
    <!-- end::global styles -->

    <!-- begin::custom styles -->
    <link rel="stylesheet" href="css/app.min.css" type="text/css">
    <link rel="stylesheet" href="css/themify-icons.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- end::custom styles -->

    <!--https://sweetalert2.github.io/-->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.min.css">
    <!---sweetalert2 alert--->

</head>

<body class="bg-white h-100-vh p-t-0">

    <div class="p-b-50 d-block d-lg-none"></div>

    <div class="container h-100-vh">
        <div class="row align-items-md-center h-100-vh">
            <div class="col-lg-6 d-none d-lg-block">
                <img class="img-fluid" src="media/image/Login.jpg" alt="...">
            </div>
            <div class="col-lg-4 offset-lg-1">
                <div class="align-items-center m-b-20 text-center">
                    <img class="img-fluid" src="media/image/logo.png" alt="...">
                </div>
                <p>Sign in to continue.</p>
                <form id="loginForm" action="">
                    <div class="form-group mb-4">
                        <input type="text" class="form-control form-control-lg" id="user_name" name="user_name" autofocus placeholder="User Name">
                    </div>
                    <div class="form-group mb-4">
                        <input type="password" class="form-control form-control-lg" id="pass" name="pass" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-uppercase mb-4">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <!-- begin::global scripts -->
    <script src="vendors/bundle.js"></script>
    <!-- end::global scripts -->

    <!-- begin::custom scripts -->
    <script src="js/app.min.js"></script>
    <!-- end::custom scripts -->

    <!---sweetalert2 alert--->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.28/sweetalert2.js"></script>
    <!---sweetalert2 alert--->

    <!---Form Validate--->
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!---Form Validate--->
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        var loginForm = $("#loginForm");

        loginForm.validate({
            rules: {
                user_name: "required",
                pass: "required",
            },
            messages: {
                user_name: '<small class="form-text text-danger">Please enter the user name.</small>',
                pass: '<small class="form-text text-danger">Please enter the Password.</small>',
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: "phpservices/login_checker.php",
                    type: "post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        if (status == 'true') {
                            Swal.fire({
                                title: 'Your are successfully login',
                                icon: 'success',
                            })
                            setTimeout(function() {
                                window.location.href = "dashboard.php";
                            }, 2000);
                        } else if (status == 'pending') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Verification Pending!',
                                footer: '<a href="">Why do I have this issue?</a>'
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Username and Password Wrong',
                                footer: '<a href="">Why do I have this issue?</a>'
                            });
                        }
                    }
                });
            }
        });
    });
</script>