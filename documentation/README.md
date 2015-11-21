

## Radio Buttons

````
    .field data_usage
    .field_type radio
    .field_label Data usage
    .field_description Check one or more
    .field_option res|Research
    .field_option gov|Government
    .field_option sch|School
    .field_option bus|Business
````

### Example

In CA to have text that is selectable you would code:


````
1.Alt1.sec=ONE User shall exercise due care to protect all Smart data from unauthorized physical and electronic access. Both parties shall establish ...

1.Alt2.sec=TWO User shall exercise due care to protect all Smart City data from unauthorized physical and electronic access. Both parties shall establish ...

1.Alt3.sec=THREE User shall exercise due care to protect all Smart City data from unauthorized physical and electronic access. Both parties shall establish ...

1.=[Z/Alt/3]
````

To select the second one you would add the following:

````
1.sec={1.Alt2.sec}
````

Coding in the .dot file would be


````
.field 1.sec
.field_type radio
.field_label Clause selection
.field_description Check one or more
.field_option {1.Alt1.sec}|Option One custom text
.field_option {1.Alt2.sec}|Option Two custom text
.field_option {1.Alt3.sec}|Option Three custom text
````



### .field_option
Options for a check box can contain a value and label or just label with the value automaticly assianged.
Format for the value/text is
````
    .field_option res|Research
````

To have the value automaticaly assigned

````
    .field_option Research
````

The first value assigned will be 1.
