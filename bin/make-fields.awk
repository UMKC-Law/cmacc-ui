BEGIN{ 
    FS="=";
    print ".page\n"; 
}
$0 != "" {

    print "\n.field " $1;
    print ".field_label " $1;
    print ".field_description Description of the field";
    print ".field_place_holder Place holder";

}



