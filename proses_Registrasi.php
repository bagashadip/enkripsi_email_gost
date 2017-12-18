<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mail Crypto</title>

    <!-- Bootstrap min CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Style CSS -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body style="background:#d99de8;">

    <div class="containers">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 layout-login ">
                <img id="imagelogin" src="img/1.png">
                <?php
                $mess_succses = isset($_GET['register']) ? $_GET['register'] : null;
                if ($mess_succses == 'gmail') {
                    echo '<div class="popup popup-link popup-container">Email harus menggunakan akun gmail<a class="popup-close" href="proses_Registrasi.php">X</a></div>';
                } else {
                    null;
                }
                ?>
                <div class="panel panel-default" style="margin-top:-9%;">
                    <div class="panel-heading">
                        <h3 class="panel-title">Silahkan Daftar Akun</h3>
                    </div>
                    <div class="panel-body">
                        <form name="form_registrasi" method="post" action="control/manipulasi_data1.php?execute=register">
                            <fieldset>
                                <input type="text" class="form-input" name="txtemail" placeholder="Email"><br>
                                <input type="password" class="form-input" name="txtpassword" placeholder="Password"><br>
                                <input type="text" class="form-input" name="txtnamauser" placeholder="Nama"><br>
                                <input type="text" class="form-input" name="txthpuser" placeholder="No. HP"><br>
                                <input class="btn btn-black" name="BtnLogin" type="submit" value="Daftar"/>
                                <table style="float:right;">
                                    <tr><td> <a href='index.php' class="btn-user" style="margin-right: 5px; text-decoration:none;">Login </a>|</td>
                                        <td><a href='proses_Gapas.php' class="btn-user" style="margin-right: 25px;text-decoration:none;margin-left: 7px;">Forgot Password</a></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="bootstrap/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

</body>

