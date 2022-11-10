<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Register Page </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        Body {
            font-family: Calibri, Helvetica, sans-serif;
            background-color: pink;
        }

        button {
            background-color: #4CAF50;
            width: 100%;
            color: orange;
            padding: 15px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
        }

        form {
            border: 3px solid #f1f1f1;
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            display: inline-block;
            border: 2px solid green;
            box-sizing: border-box;
        }

        button:hover {
            opacity: 0.7;
        }


        .container {
            padding: 25px;
            background-color: lightblue;
        }

        a {
            height: 20px;
        }
    </style>
</head>

<body class="bg-white mt-5 py-5">
    <form id="registerForm" action="{{ url('/add-register') }}" method="POST">@csrf
        <div class="container col-md-5">
            <h1 class="text-center"> Register Form </h1>
            <label>Enter email : </label>
            <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
            <span class="error-font text-danger d-flex" id="email_error"></span>

            <label>Password : </label>
            <input type="password" class="form-control" placeholder="Enter Password" name="password" required>
            <span class="error-font text-danger d-flex" id="password_error"></span>

            <label> First Name : </label>
            <input type="text" class="form-control" placeholder="Enter first name" name="fname" required>
            <span class="error-font text-danger d-flex" id="fname_error"></span>


            <div class="d-inline-flex">
                <button type="button" id="registerId" class="btn btn-primary font-weight-800">Register</button>
            </div>
            <p> Sign In ? <a href="{{ url('/') }}">Login</a></p>



        </div>

        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script>
        $('body').on('click', '#registerId', function() {
            var registerForm = $("#registerForm");
            var formData = registerForm.serialize();
            $('#email_error').html("");
            $('#fname_error').html("");
            $('#password_error').html("");

            $.ajax({
                url: '{{ url('register/add') }}',
                type: 'POST',
                data: formData,
                success: function(data) {
                    console.log(data);
                    //alert(data);
                    if (data.errors) {
                        if (data.errors.email) {
                            $('#email_error').html(data.errors.email[0]);
                        }
                        if (data.errors.fname) {
                            $('#fname_error').html(data.errors.fname[0]);
                        }
                        if (data.errors.password) {
                            $('#password_error').html(data.errors.password[0]);
                        }

                    }
                    if (data.success) {
                        window.location.replace('{{route("dashboard.index")}}');
                    }
                },
            });
        });
    </script>
</body>

</html>
