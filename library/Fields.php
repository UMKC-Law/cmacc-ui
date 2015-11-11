<?php
/**
 * Created by PhpStorm.
 * User: paulb
 * Date: 10/27/15
 * Time: 5:22 PM
 */

class Fields
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

    function add_field($name, $label = '', $value = '', $place_holder = '', $type = 'text', $required = '', $description= '', $cmacc_id = 0)
    {
        $this->fields[] = array(
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'place_holder' => $place_holder,
            'type' => $type,
            'required' => $required,
            'description' => $description,
            'cmacc_id' => $cmacc_id
        );

        return sizeof($this->fields) - 1;

    }

    function update_field( $field_name, $var, $value ) {

        if ( $i = $this->get_field_index_by_name( $field_name )) {
            $this->fields[$i][$var] = $value;
            return true;
        }

        return false;
    }

    function get_field_index_by_name( $field_name ) {
        foreach ($this->fields AS $i => $v) {

            if ( $v['name'] == $field_name ) {;
                return $i;
            }
        }
        return false;
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

            if (empty($name)) continue;

            $name = $v['name'];
            $value = $v['value'];
            $label = $v['label'];
            $place_holder = $v['place_holder'];
            $type = $v['type'];
            $required = $v['required'];
            $description = $v['description'];

            $html .= $this->paint_field( $name, $value, $label, $place_holder, $type, $required, $description);

        }

        return $html;
    }

    function paint_field( $name, $value = '', $label = '', $place_holder = '', $type = '', $required ='', $description ='' )
    {



        $name = str_replace( ' ', '_', $name );         // Remove white space
        $name = str_replace( '.', '_', $name );         // Remove white space

        if ( empty( $label ) ) $label = $name;

        $f = <<<EOM
        <row class="cmacc-field-input">
            <div class="col-lg-8">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="$name">$label</label>

                        <div class="col-md-9">
                            <input id="$name" name="$name" placeholder="$place_holder"
                                   class="form-control input-md" type="text" value="$value">
                        </div>
                    </div>
             </div>
             <div class="col-lg-4">
                $description
             </div>

        </row>
EOM;


        return $f;


    }


}