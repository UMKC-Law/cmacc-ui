<?php
/**
 * Created by PhpStorm.
 * User: paulb
 * Date: 11/13/15
 * Time: 8:30 PM
 */

class OpenEditSave
{

    function __construct($path,$dir)
    {

        if (isset($_REQUEST['submit'])) {

            $file_name = $path . $dir;

            if (file_exists($file_name)) {

                if (is_writeable($file_name)) {

                    $includes = array();

                    /**
                     * Open file
                     */

                    $lib_path = LIB_PATH;
                    $document = `perl $lib_path/openedit-parser.pl $path/$dir`;
                    $document .= "\nWAS=" . date("Y/m/d") . " : " . time() . "\n\n";
                    $document .= file_get_contents($path . $dir, FILE_USE_INCLUDE_PATH);

                    /**
                     * Load needed objects to process form
                     */
                    include("$lib_path/QNA.php");
                    $QNA = new QNA("$path/$dir");

                    include("$lib_path/Fields.php");
                    $fields = new Fields();

                    $QNA->process_form_file($fields);


                    /**
                     * Load fields from the input file
                     */
                    /*
                                        $lines = explode("\n", $document);

                                        foreach ($lines AS $line) {
                                            preg_match("/(.*)=(.*)/", $line, $field);

                                            if (sizeof($field) == 0) {
                                                continue;                                   // skip blank lines
                                            }  else {

                                                $field_name = $field[1];
                                                $field_value = $field[2];

                                                $fields->add_cmacc_field($field_name, $field_value);
                                            }


                                        }
                    */
                    $includes[] = '=[H4KC/Form/Master_DSA.md]';

                    /**
                     * Process post variables
                     */

                    foreach ($_POST AS $fld => $val) {
                        $fields->update_field_by_html_name($fld, 'value', $val);
                    }


                    $cmacc_document = $fields->format_fields_for_cmacc();

                    foreach ($includes AS $include) {
                        $cmacc_document .= "\n$include\n";
                    }


                    $fp = fopen($file_name, "w");


                    fwrite($fp, $cmacc_document);
                    fclose($fp);
                } else {
                    print '<span style="color: red">ERROR: File ' . $dir . ' is not write able.</style>';
                }
            } else {
                print '<span style="color: red">ERROR: File ' . $dir . ' does not exists.</style>';
            }
        }
    }
}
