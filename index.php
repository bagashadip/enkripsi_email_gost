<?php
    error_reporting(0);
    include ('proses_Login1.php');

    session_start();
    if (isset($_SESSION['access_token'])) {
        header("location: home.php");
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RTV-Mail</title>

    <!-- Bootstrap min CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Style CSS -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body style="background:#d99de8;">
    <div class="col-md-4 col-md-offset-4 layout-login">
        <img id="imagelogin" src="img/11.png">
        <div class="panel panel-default " style="margin-top:-7%;">
            <div class="panel-heading">
                <h3 class="panel-title">Please Login</h3>
                <h3 class="panel-title"><?php echo $_SESSION['access_token']; ?></h3>
            </div>
            <div class="panel-body">
                <form role="form">
                    <fieldset>
                        <?php if (isset($loginUrl) || $authException) { ?>
                            <!-- Change this to a button or input when using this as a form -->
                            <a  href="<?php echo $loginUrl; ?>" class="btn btn-lg btn-black btn-block" >Login My Gmail</a>
                        <?php } else { ?>
                            <?php echo $notice; ?>
                        <?php } ?>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="bootstrap/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

</body>

