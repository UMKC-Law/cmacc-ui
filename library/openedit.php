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
        }

        .starter-template {
            padding: 40px 15px;
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
</head>
<body>

<?php

ini_set("allow_url_include", true);

$lib_path = LIB_PATH;
$document = `perl $lib_path/openedit-parser.pl $path/$dir`;
$document .= "\nWAS=" . date("Y/m/d") . " : " . time() . "\n\n";
$document .= file_get_contents($path . $dir, FILE_USE_INCLUDE_PATH);


include("$lib_path/view-tabs-bootstrap.php");


class Screen
{
    var $cmacc_fields = array();                                        // Fields from cmacc
    var $cmacc_id = 0;                                                  // Index or row number;

    var $fields = array();                                              // Fields defined by the user

    function __construct()
    {
        $this->fields[] = array(
            'name' => 'Ti',
            'label' => 'Title',
            'value' => 'Term of Confidentiality',
            'place_holder' => 'Enter: Title to Document',
            'type' => 'text',
            'required' => '',
            'cmacc_id' => 0
        );
        $this->fields[] = array(
            'name' => 'SecName',
            'label' => 'Section Name',
            'value' => '',
            'place_holder' => 'Enter: Section Title',
            'type' => 'text',
            'required' => '',
            'cmacc_id' => 0
        );

    }

    function add_cmacc_field($name, $value)
    {

        $name = trim($name);                                            //  Remove any extra white space

        $this->cmacc_id++;
        $this->cmacc_fields[$this->cmacc_id] = array(
            'name' => $name,
            'label' => $name,
            'value' => $value
        );

        $field_offset = $this->get_field_offset($name);

        if ($field_offset === FALSE) {
            $field_offset = $this->add_field($name, $name, $value);
        }

        $this->fields[$field_offset]['cmacc_id'] = $this->cmacc_id;

    }

    function get_field_offset($name)
    {

        foreach ($this->fields AS $i => $v) {
            if ($v['name'] == $name) return $i;
        }

        return false;
    }

    function add_field($name, $label = '', $value = '', $place_holder = '', $type = 'text', $required = '', $cmacc_id = 0)
    {
        $this->fields[] = array(
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'place_holder' => $place_holder,
            'type' => $type,
            'required' => $required,
            'cmacc_id' => $cmacc_id
        );

        return sizeof($this->fields) - 1;

    }

    function dump()
    {
        var_dump($this->fields);
        var_dump($this->cmacc_fields);
    }

    function paint_fields()
    {

        $html = '';

        foreach ($this->fields AS $i => $v) {

            $name = $v['name'];
            $value = $v['value'];
            $label = $v['label'];
            $place_holder = $v['place_holder'];
            $type = $v['type'];
            $required = $v['required'];

            if (empty($name)) continue;

            $f = <<<EOM
                <div class="form-group">
                    <label class="col-md-3 control-label" for="$name">$label</label>

                    <div class="col-md-9">
                        <input id="$name" name="$name" placeholder="$place_holder"
                               class="form-control input-md" required="" type="text" value="$value">
                    </div>
                </div>
EOM;


            $html .= $f;

        }

        return $html;
    }


}

$screen = new Screen();

?>

<div class="container">

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


    <?php
    $lines = explode("\n", $document);

    foreach ($lines AS $line) {
        preg_match("/(.*)=(.*)/", $line, $field);

        if (sizeof($field) == 0) continue;             // skip blank lines

        $field_name = $field[1];
        $field_value = $field[2];


        $screen->add_cmacc_field($field_name, $field_value);


    }

    ?>


    <div class="row">

        <form class="form-horizontal">
            <fieldset>

                <!-- Form Name -->
                <legend>CMACC FORM</legend>

                <hr>

                <?php echo $screen->paint_fields(); ?>

                <hr>

                <!-- Multiple Radios -->
                <div class="form-group">
                    <label class="col-md-3 control-label" for="radios">Obligations</label>

                    <div class="col-md-9">
                        <div class="radio">
                            <label for="radios-0">
                                <input name="radios" id="radios-0" value="1" checked="checked" type="radio">
                                {The_Receiving_Party}'s obligations with respect to all
                                {Confidential_Information_of_the_Disclosing_Party} will terminate only pursuant to
                                {Conf.Exclude.Xref}.
                            </label>
                        </div>
                        <div class="radio">
                            <label for="radios-1">
                                <input name="radios" id="radios-1" value="2" type="radio">
                                {The_Receiving_Party}'s obligations with respect to all
                                {Confidential_Information_of_the_Disclosing_Party} will terminate on the earlier of
                                {Confidentiality.End.YMD} or when all such information has become subject to an
                                exclusion from confidentiality pursuant to {Conf.Exclude.Xref}.
                            </label>
                        </div>
                        <div class="radio">
                            <label for="radios-2">
                                <input name="radios" id="radios-2" value="3" type="radio">
                                {The_Receiving_Party}'s obligations with respect to each element of
                                {Confidential_Information_of_the_Disclosing_Party} will terminate on the earlier of
                                {DurationOfConfidentiality} after the time such element was communicated by
                                {the_Disclosing_Party} to {the_Receiving_Party} or when all such information has become
                                subject to an exclusion from confidentiality pursuant to {Conf.Exclude.Xref}.
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Button (Double) -->
                <div class="form-group">
                    <div class="col-md-12">
                        <button id="button1id" name="button1id" class="btn btn-primary">Save</button>
                        <a href="#" id="button2id" name="button2id" class="btn btn-danger">Cancel</a>
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="public/jquery/jquery.min.js"></script>
<script src="public/bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="public/bootstrap/js/ie10-viewport-bug-workaround.js"></script>
