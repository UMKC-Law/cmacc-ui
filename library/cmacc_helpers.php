<?php
error_reporting(E_ALL);
$path = ROOT . '/Doc/';

//$URLForDocsInRepo = URLFORDOCSINREPO ;

//$Render_the_Document= "Render the Document";

//$Completions_Message = "Open Completions - copy from here, paste into your document, and complete:";

$Text_Edit_Window_Size = TEXTEDITWINDOWSIZE ;

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = "landing";
}

if (isset($_REQUEST['file'])) {
    $dir = $_REQUEST['file'];
    $dir = str_replace('..', '', $dir);
} else {
    $dir = './';
}


switch ($_REQUEST['action']) {

    case 'list':

        include('list.php');
        break;


    case 'source':

        if (isset($_REQUEST['submit'])) {

            $file_name = $path . $dir;

            if (file_exists($file_name)) {

                if (is_writeable($file_name)) {
                    $fp = fopen($file_name, "w");
                    $data = $_REQUEST['newcontent'];
                    $data = preg_replace('/\r\n/', "\n", $data);
                    $data = trim($data);
                    fwrite($fp, $data);
                    fclose($fp);
                } else {
                    print '<span style="color: red">ERROR: File ' . $dir . ' is not write able.</style>';
                }
            } else {
                print '<span style="color: red">ERROR: File ' . $dir . ' does not exists.</style>';
            }
        }

        $content = file_get_contents($path . $dir, FILE_USE_INCLUDE_PATH);
        $contents = explode("\n", $content);
        $rootdir = pathinfo($dir);
        $filenameX = basename($dir);

        //source.php includes the formatting for the table that displays the components of a document
        include("source.php");

        break;

    case 'render':

        if (isset($_REQUEST['submit'])) {
            echo "RENDERING...........<br>";
        } else {
            echo "not rending ... <br>";
        }

        if (isset($_REQUEST['file'])) {
            echo "</div></div>";
        }
        break;

    case 'edit':

        include('edit.php');
        break;

    case 'edit-form':

        include('edit-form.php');
        break;

    case 'save-form':

        $file_name_info = pathinfo($path . $dir);

        $file_dir = $file_name_info['dirname'];
        $file_name = $file_name_info['filename'];

        $file_name_to_edit = "$file_dir/$file_name.dot";

        if (file_exists($file_name_to_edit)) {


                if (is_writeable($file_name_to_edit)) {
                    $fp = fopen($file_name_to_edit, "w");
                    $data = $_REQUEST['newcontent'];
                    $data = preg_replace('/\r\n/', "\n", $data);
                    $data = trim($data);
                    fwrite($fp, $data);
                    fclose($fp);
                } else {
                    print '<span style="color: red">ERROR: File ' . "$file_name_to_edit" . ' is not write able.</style>';
                }

        } else {

            $contents = "NOTHING TO EDIT";
        }

        include('save-form.php');
        include('openedit.php');
        break;

    case 'openedit':

        include('openedit.php');
        break;

    case 'openedit-save':



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
print "<pre>";
$fields->dump();
                    /**
                     * Process post variables
                     */

                    foreach ($_POST AS $fld => $val ) {
                        $fields->update_field_by_html_name($fld,'value',$val);
                    }
print "--------------------------------------------------\n";
$fields->dump();


                    $fp = fopen($file_name, "w");


                    die;

 //                   $data = $_REQUEST['newcontent'];
 //                   $data = preg_replace('/\r\n/', "\n", $data);
 //                   $data = trim($data);
 //                   fwrite($fp, $data);
      //              fclose($fp);
                } else {
                    print '<span style="color: red">ERROR: File ' . $dir . ' is not write able.</style>';
                }
            } else {
                print '<span style="color: red">ERROR: File ' . $dir . ' does not exists.</style>';
            }
        }
        //source.php includes the formatting for the table that displays the components of a document

        $content = file_get_contents($path . $dir, FILE_USE_INCLUDE_PATH);
        $contents = explode("\n", $content);
        $rootdir = pathinfo($dir);
        $filenameX = basename($dir);

        include("source.php");
        break;

    default:
        include($_REQUEST['action'] . '.php');
        break;
}

