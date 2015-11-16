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
            padding-top: 50px;
            color: #212121;
        }

        .starter-template {
            padding: 40px 15px;
        }

        .input-group {
            background-image: none;
            background-color: #fafafa;
            padding-top: 2em;
            margin-top: 2em;
            padding-left: 30px;
            padding-right: 5px;
        }

        .cmacc-field-input {
            margin-top: 2em;
            padding-top: 2em;
        }

        p.header {
            font-size: 20px;
            line-height: 32px;
            display: block;
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
        }

        h1.header {
            font-size: 34px;
            font-weight: 400;
            line-height: 40px;
            margin-bottom: 30px;
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

ini_set("allow_url_include", true);

$lib_path = LIB_PATH;
$document = `perl $lib_path/openedit-parser.pl $path/$dir`;
$document .= "\nWAS=" . date("Y/m/d") . " : " . time() . "\n\n";
$document .= file_get_contents($path . $dir, FILE_USE_INCLUDE_PATH);


include("$lib_path/view-tabs.php");

include("$lib_path/QNA.php");
$QNA = new QNA("$path/$dir");

?>


<div class="container">

    <div class="row">

        <?php if ($QNA->dot_file_exists()) { ?>

            <form class="form-horizontal" method="post">
                <fieldset>

                    <?php


                    include("$lib_path/Fields.php");
                    $fields = new Fields();
                    $lines = explode("\n", $document);

                    foreach ($lines AS $line) {
                        preg_match("/(.*)=(.*)/", $line, $field);
                        if (sizeof($field) == 0) continue;             // skip blank lines

                        if (strlen($field[1]) == 0) {
                            if (preg_match("/^=\[(.*)\]/", $line, $include_file_name)) {
                                $include_line = $include_file_name[0];
                                $fields->add_ca_include_file($include_file_name);
                            }
                        } else {

                            $field_name = $field[1];
                            $field_value = $field[2];

                            $fields->add_cmacc_field($field_name, $field_value);
                        }
                    }

                    $QNA->process_form_file($fields);

                    ?>
                </fieldset>
                <input class="btn btn-info" type="submit" name="submit" value="Save">
                <input type="hidden" name="file" value="<?php echo $dir; ?>">
                <input type="hidden" name="action" value="openedit-save">
            </form>
        <?php } else { ?>
            <div class="starter-template">
                <h1><?php echo $dir; ?></h1>

                <div id="tab-edit">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <textarea id="textedit" <?php echo TEXT_EDIT_WINDOW_SIZE; ?> name="newcontent"
                          style="<?php echo TEXT_EDIT_AREA_STYLE; ?>">

<?php echo $document; ?>



                </textarea><br>
                        <input class="btn btn-info" type="submit" name="submit" value="Save">
                        <input type="hidden" name="file" value="<?php echo $dir; ?>">
                        <input type="hidden" name="action" value="source">
                    </form>


                </div>
            </div>
        <?php } ?>
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

