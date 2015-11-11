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

</head>
<body>
<?php
/*require('./vendor/autoload.php'); */
ini_set("allow_url_include", true);


//This displays the path, current file name, and provides the edit and show options //

$lib_path = LIB_PATH;

$lib_path = LIB_PATH;
$document = `perl $lib_path/parser.pl $path/$dir`;

$minDocLength = 1;

if (strlen($document) > $minDocLength) {

    $document = str_replace("{", "<font color='red'>{", $document);
    $document = str_replace("}", "}</font>", $document);

//kludge to let html headers have formatting.

    $document = str_replace("(Curly-)", "{", $document);
    $document = str_replace("(-Curly)", "}", $document);


    $html = $document;
} else {
    $html = "Nothing to Show";
}

include("$lib_path/view-tabs.php");


?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php echo $html; ?>
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

