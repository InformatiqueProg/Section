# THELIA MODULE - SECTION
----

## Installation

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is Section.
* Activate it in your thelia administration panel

## Need to add ```section``` in a description ?
The Section module requires the [SmartyFilter module](https://github.com/thelia-modules/SmartyFilter).
It is used to replace shortcodes with their corresponding HTML section codes.
[Install it](https://github.com/thelia-modules/SmartyFilter#installation) and activate it before activating the Section module.

### Exemple

insert this ```[moduleSection_2]``` where you need in your description (where 2 is section ID).

## HOW TO USE

Go the thelia administration panel, Then click on "Tools > Section" to manage the ```section```.

## LOOP

### Input arguments

| Argument | Description |
| -------- | ----------- |
| id | The Section IDs in the database, separated by commas |
| visible | A boolean value. default : 1  |

### Ouput arguments

| Variable | Description |
| -------- | ----------- |
| $ID | Section ID |
| $IS_TRANSLATED | check if the content is translated |
| $LOCALE | The locale used for this research |
| $VISIBLE | visible status |
| $TITLE | Section title |
| $DESCRIPTION | Section description |

### Exemple
To add the HTML content of a ```section```, add this loop to your template
```html
{loop name="section_footer" type="section" id=1}
    {$DESCRIPTION nofilter}
{/loop}
```