<?php
/**
 * Created by PhpStorm.
 * User: paulb
 * Date: 10/27/15
 * Time: 11:56 AM
 */

class QNA {
    var $file_name = 'test.dot';

    var $fh = false;

    var $stack = array();

    var $stack_i = -1;
    var $type_stack = array();

    var $pages = array();
    var $page_number = 0;

    var $tab_stack = array();                                                       // where we store the content of the tabs
    var $tabs = array();                                                            // meta data about the tabs

    var $tab_group = 0;                                                             // indexs into $tab_stack and $tabs
    var $tab_number = 0;

    var $in_field = false;

    public function __construct($full_file_name)
    {

        $file_name_info = pathinfo($full_file_name);

        $dir = $file_name_info['dirname'] . '/';
        $file_name = $file_name_info['filename'];

        $this->file_name = "$dir/$file_name.dot";

        if ( file_exists( $this->file_name) ) {
            return $this->fh = fopen($this->file_name, "r");
        } else {
            return false;
        }


    }

    public  function dot_file_exists() {

        if ( is_object( $this->fh ) ) {
            return true;
        } else {
            return false;
        }

}

    public function process_form_file( $Fields ) {

        $this->Fields = $Fields;

        while ( $line = fgets($this->fh)) {

            if ($s = preg_match("/\.([a-z_\-]*)\s(.*)|(.*)/", $line, $field)) {

                if (!empty($field[1])) {

                    $field_name = $field[1];
                    $field_value = $field[2];

                    switch ( $field_name ) {
                        case 'page':


                            if ($this->stack_i != -1 ) {
                                $this->end_page( );
                            }


                            $this->start_page($field_value);

                            break;


                        case 'tab':

                            if ( $this->in_field ) {
                                $this->end_field();
                            }


                            if  ($this->type_stack[$this->stack_i] != 'tab') {       // If we are not in a tab group create it
                                $this->start_tab_group();
                            } else {
                                $this->end_tab();
                            }
                            $this->start_tab($field_value);                                                 // Then start a tab


                            break;

                        case 'field':
                            $this->start_field($field_value);
                            break;

                        case 'field_label':
                            $this->update_field( $this->in_field, 'label', $field_value );
                            break;

                        case 'field_description':
                            $this->update_field( $this->in_field, 'description', $field_value );
                            break;

                        case 'field_place_holder':
                            $this->update_field( $this->in_field, 'place_holder', $field_value );
                            break;

                        default:
                            print_r($field);
                            break;

                    }

                } else {
                    $this->stack[$this->stack_i][] = $field[0];
                }
            } else {
                print "2- $line \n";
                $this->stack[$this->stack_i][] = $field[0];
            }

        }
        if ($this->stack_i != -1 ) {                // Print the current page if there is one.


            $this->end_page( 1 );

        }
        print "</div>";                             // This is a sign of a bug....

    }

    function start_page($field) {
        $this->stack_i++;
        $this->page_number++;
        //  $this->stack[$this->stack_i] = '';
        $this->type_stack[$this->stack_i] = 'page';


        $hidden = $this->page_number == 1 ? '' : ' style="display: none;" ';

        list( $page_id, $page_title) = $this->get_page_name_description($field);

        $page_id = 'page_' . $this->page_number;

        $this->stack[$this->stack_i][] = "\n<div id=\"$page_id\" class=\"screen-page\" $hidden >\n";

        if ( !empty( $page_title) ) {
            $this->stack[$this->stack_i][] = "<h1>$page_title</h1>\n";
        }
    }

    function end_page( $last_page = 0 )
    {

        if ( $this->in_field ) {
            $this->end_field();
        }

        if ($this->type_stack[$this->stack_i] != 'page') {
            $this->end_tab_group();
        }

        foreach ($this->stack[$this->stack_i] AS $i => $line) {
            if ($i > 0) print "\t";
            print $line . "\n";
        }

        print "<div class=\"page-bottom-nav\">";
        if ($this->page_number != 1) {
            $prev_page = 'show_page="page_' . ($this->page_number - 1) . '"';
            print '<a href="#" ' . $prev_page . ' class="btn btn-default page_button" role="button">Prev</a>';
        } else {
            $prev_page = 'show_page="page_1"';
        }

        $next_page = 'show_page="page_' . ($this->page_number + 1) . '"';

        if ( $last_page == 0 ) {
            print '<a href="#" ' . $next_page . ' class="btn btn-primary page_button" role="button">Next</a>';
        }

        print "</div>";

        print '</div> <!-- end page div -->' . "\n";


        unset($this->stack[$this->stack_i]);
        unset($this->type_stack[$this->stack_i]);
        $this->stack_i--;

    }

    function start_tab_group() {

        $this->tab_group++;
        $this->stack_i++;

        $this->type_stack[$this->stack_i] = 'tab-group';
        //  $this->stack[$this->stack_i][] = '  TAB GROUP START';
    }

    function end_tab_group() {

        $this->end_tab();

        $this->stack[$this->stack_i -1][] = "\n<div class=\"start-of-tab-group\">";
        $this->stack[$this->stack_i -1][] = "\t<ul class=\"nav nav-tabs\" role=\"tablist\">\n";

        foreach ( $this->tabs[$this->tab_group] AS $tab_i => $tab ) {

            $id = $tab['id'];
            $title = $tab['title'];
            $active = $tab_i == 1 ? ' active' : '';
            $this->stack[$this->stack_i -1][] = "\t\t<li role=\"presentation\" class=\"$active\"><a href=\"#$id\" aria-controls=\"$id\" role=\"tab\" data-toggle=\"tab\">$title</a></li>\n";
        }

        $this->stack[$this->stack_i -1][] = "</ul>\n";


        $this->stack[$this->stack_i -1][] = "<div class=\"tab-content\">\n";

        foreach ( $this->tabs[$this->tab_group] AS $tab_number => $tab ) {

            $id = $tab['id'];
            $title = $tab['title'];

            $active = $tab_number == 1 ? ' active' : '';
            $this->stack[$this->stack_i -1][] = "\t<div role=\"tabpanel\" class=\"tab-pane$active\" id=\"$id\">\n";

            foreach ($this->tab_stack[$this->tab_group][$tab_number] AS $line) {
                $this->stack[$this->stack_i -1][] = "\t\t$line";
            }

            $this->stack[$this->stack_i -1][] = "\t</div>\n";
        }


        $this->stack[$this->stack_i -1][] = "</div>\n";
        $this->stack[$this->stack_i -1][] = "</div>\n";

        $this->stack_i--;

    }

    function start_tab($field) {
        $this->tab_number++;
        $this->stack_i++;

        $this->type_stack[$this->stack_i] = 'tab';
        //    $this->stack[$this->stack_i][] = '  TAB START';

        list( $tab_id, $tab_title) = $this->get_tab_name_description($field);

        $this->tabs[$this->tab_group][$this->tab_number] = array('id' => $tab_id, 'title' => $tab_title);

    }

    function end_tab() {
        //      $this->stack[$this->stack_i][] = '  TAB END';
        foreach ( $this->stack[$this->stack_i] AS $i => $line ) {
            if ( $i > 0 ) $line =  "\t\t" . $line;
            $this->tab_stack[$this->tab_group][$this->tab_number][] = "\t" . $line;
        }
        unset($this->stack[$this->stack_i]);
        unset($this->type_stack[$this->stack_i]);
        $this->stack_i--;

    }

    function start_field($field) {

        if ( $this->in_field ) {
            $this->end_field();
        }

        $this->Fields->add_field( $field );
        $this->in_field = $field;

    }

    function update_field( $field_id, $attribute, $attribute_value ) {
        return $this->Fields->update_field( $field_id, $attribute, $attribute_value );
    }

    function end_field () {
        $i = $this->Fields->get_field_index_by_name( $this->in_field );

        if ( $i ) {
            $v = $this->Fields->fields[$i];
            $name = $v['name'];
            $value = $v['value'];
            $label = $v['label'];
            $place_holder = $v['place_holder'];
            $type = $v['type'];
            $required = $v['required'];

            $html = $this->Fields->paint_field( $name, $value, $label, $place_holder, $type, $required);

            $this->stack[$this->stack_i][] = $html;

        }

        $this->in_field = false;
    }



    function get_page_name_description($field) {

        $page_no = sizeof( $this->pages );
        $page_id = 'page_' . $page_no;
        $page_title = '';

        if ( !empty ($field) ) {

            $ret = explode('|',$field, 2);

            if ( sizeof ($ret) == 1 ) {
                $page_title = $ret[0];
            } else {
                $page_id = $ret[0];
                $page_title = $ret[1];
            }

        }

        $this->pages[] = array( 'id' => $page_id, 'title' => $page_title);

        return array($page_id, $page_title);
    }

    function get_tab_name_description($field) {

        $tab_no = sizeof( $this->tabs );
        $tab_id = 'tab_' . $tab_no;
        $tab_title = '';

        if ( !empty ($field) ) {

            $ret = explode('|',$field, 2);

            if ( sizeof ($ret) == 1 ) {
                $tab_title = $ret[0];
            } else {
                $tab_id = $ret[0];
                $tab_title = $ret[1];
            }

        }

        $this->tabs[] = array( 'id' => $tab_id, 'title' => $tab_title);

        return array($tab_id, $tab_title);
    }

}