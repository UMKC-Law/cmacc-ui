<?php

/**
 * Created by PhpStorm.
 * User: paulb
 * Date: 11/13/15
 * Time: 8:30 PM
 */
class OpenEditSave
{

    function __construct($path, $dir)
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


                    $lines = explode("\n", $document);

                    foreach ($lines AS $line) {
                        preg_match("/(.*)=(.*)/", $line, $field);
                        if (sizeof($field) == 0) continue;             // skip blank lines

                        if (strlen($field[1]) == 0) {
                            if (preg_match("/^=\[(.*)\]/", $line, $include_file_name)) {
                                $include_line = $include_file_name[0];
                                $fields->add_ca_include_file($include_line);
                            }
                        }
                    }

                    $QNA->process_form_file($fields);

                    /**
                     * Process post variables
                     */

                    foreach ($_POST AS $fld => $val) {
                        $fields->update_field_by_html_name($fld, 'value', $val);
                    }

                    $cmacc_document = $fields->format_fields_for_cmacc();

                    $cmacc_document .= $fields->format_include_files_for_cmacc();


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
