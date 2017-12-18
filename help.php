<?php
include ("proses_Login1.php");
error_reporting(0);

if (!isset($_SESSION['access_token'])) {

    header("location: index.php");
}
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RTV-Mail</title>

    <link href="bootstrap/css/bootstrap-theme-united.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet">

    <!--    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
    <style>
        iframe {
            width: 100%;
            border: 0;
            min-height: 80%;
            height: 600px;
            display: flex;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><h3>RTV-Mail</h3></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php #if (isset($_SESSION['access_token'])) {?>
                    <li><a href="?logout" class="size"><img style="margin-right: 10px; margin-top: -2px;" src="img/logout.png" width="25" height="25">Logout</a></li>

                    <?php #} else { ?>

                    <?php #}?>
                </ul>

            </div><!--/.nav-collapse -->
        </div>
    </nav>


    <div class="container-fluid">
        <div class="navbar-default sidebar" role="navigation">
            <div class="col-sm-2 col-md-2 sidebar">
                <ul class="nav" id="side-menu">
                    <!--   <li>
                            <a href="#compose-modal" data-toggle="modal" id="compose-button" class="linkweb">
                                <img style="margin-right: 10%;" src="img/write.png" width="30" height="30">Compose 1</a>
                        </li> !-->
                    <li>
                        <a class="linkweb" href="compose.php"><img style="margin-right: 10%;" src="img/write.png" width="30" height="30">  Compose </a>
                    </li>
                    <li>
                        <a class="linkweb" href="inbox.php"><img style="margin-right: 10%;" src="img/inbox.png" width="30" height="30"></i>  Inbox</a>
                    </li>
                    <li>
                        <a class="linkweb" href="sent.php"><img style="margin-right: 10%;" src="img/send.png" width="30" height="30">  Sent Mail</a>
                    </li>
                    <li>
                        <a class="linkweb" href="dekrip.php"><img style="margin-right: 10%;" src="img/file-dec.png" width="30" height="30">  Dekripsi</a>
                    </li>
                    <li>
                        <a class="linkweb" href="help.php"><img style="margin-right: 10%;" src="img/help.png" width="30" height="30">  Help</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

<body>
    <div id="page-wrapper">
        <div class="col-lg-12">
            <?php echo $notice; ?>
            <h3 class="page-header">Halaman bantuan, untuk memudahkan pengguna menggunakan Mail Crypto <img id="imagestyle" src="img/help.png"></h3>
        </div>	
        <!-- /.row -->

        <div class="panel-body">
            <p>
                <button type="button" class="btn btn-lg btn-default" align="center">Compose</button>&nbsp; Menu Compose ini digunakan untuk mengirim pesan email ke alamat tujuan dan harus menginput To, Subject, Message dan File.<br><br> 
                <button type="button" class="btn btn-lg btn-default" align="center">Inbox</button>&nbsp; Menu Inbox digunakan jika ingin membaca kotak masuk email, dengan cara mengklik subject email yang akan dibaca dan download file dengan klik nama file<br><br>
                <button type="button" class="btn btn-lg btn-default" align="center">Sent Mail</button>&nbsp; Menu Sent Mail digunakan jika ingin membaca pesan terkirim email<br><br>
                <button type="button" class="btn btn-lg btn-default" align="center">Dekripsi</button>&nbsp; Menu Dekripsi digunakan untuk mengembalikan file yang di enkripsi menjadi file seperti semula.<br><br> 
                <button type="button" class="btn btn- btn-default" align="center">Help</button>&nbsp; Menu Help digunakan untuk mendapatkan informasi mengenai fungsi dari aplikasi Mail Crypto.<br><br>
                <button type="button" class="btn btn-lg btn-default" align="center">About</button>&nbsp; Menu About digunakan untuk mengetahui informasi mengenai pengembang aplikasi<br><br>
                <button type="button" class="btn btn-lg btn-default" align="center">Logout</button>&nbsp; Button Logout digunakan untuk keluar dari aplikasi Mail Crypto<br>
            </p>
        </div>
    </div> 

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
</body>

</html>
