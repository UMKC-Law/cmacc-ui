<?php
error_reporting(E_ALL);
$path = ROOT . '/Doc/';

//$URLForDocsInRepo = URLFORDOCSINREPO ;

//$Render_the_Document= "Render the Document";

//$Completions_Message = "Open Completions - copy from here, paste into your document, and complete:";

$Text_Edit_Window_Size = TEXT_EDIT_WINDOW_SIZE;

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

        include('open-edit-save.php');

        $obj = new OpenEditSave($path, $dir);

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

