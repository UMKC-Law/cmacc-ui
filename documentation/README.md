

## Checkboxes

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
