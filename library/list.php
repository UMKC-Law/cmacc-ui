<?php
/**
 * Displays
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>CMACC</title>

    <!-- Bootstrap core CSS -->
    <link href="public/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <style>
        body {
            padding-top: 70px;
        }
    </style>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="public/bootstrap/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="public/bootstrap/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <![endif]-->

    <link  href="<?php echo ASSETS_PATH; ?>/custom.css" rel="stylesheet" />

</head>
<body>
<?php

ini_set("allow_url_include", true);

$lib_path = LIB_PATH;

include("$lib_path/view-tabs.php");

?>


<div class="row ">
    <div class="container">
        <div class="col-lg-12">

            <?php

            if ( $dir == '' ) $dir = './';

            if (!($dir == 'ssssss')) {
                $rootdir = pathinfo($dir);

                echo "<div id='updir'><h3 class='sc subtitle'><a href=index.php?action=list&file=><img src='" . ASSETS_PATH . "/arrowup.png' height=25></a>
<a href=$_SERVER[PHP_SELF]?action=list&file=" . $rootdir['dirname'] . "/>" . $rootdir['dirname'] . "</a>/" . $rootdir['filename'] . "</h3><br>";

                echo "<center><a href=" . URLFORDOCSINREPO . $dir . ">Github</a> &emsp;</div>";

            }

            $files = scandir($path . $dir);

            if (file_exists($path . $dir . 'include.php')) {
                echo "<div class='includers'>";
                include $path . $dir . 'include.php';
                echo "</div>";
            }

            echo '<div class="listings">';
            echo "<div id='content-list'>";
            foreach ($files as $f) {
                if (is_dir($path . $dir . $f)) {
                    if (!(($f == '.') || ($f == '..') || ($f == '.git'))) {

                        echo "<br><a href=$_SERVER[PHP_SELF]?action=list&file=$dir$f/><img height=20 src='" . ASSETS_PATH . "/folder.png'> $f</a>";
                    }
                } else {
                    if (!(($f == 'include.php') || preg_match('/^\./', $f))) {
                        echo "<br><a href=$_SERVER[PHP_SELF]?action=source&file=$dir$f><img height=20 src='" . ASSETS_PATH . "/play.png'> $f</a>";
                    }
                }
            }
            echo "</div></div>";
            ?>

        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="public/jquery/jquery.min.js"></script>
<script src="public/bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="public/bootstrap/js/ie10-viewport-bug-workaround.js"></script>
<script src="public/js/form.js"></script>

