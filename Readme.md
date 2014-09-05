#THELIA MODULE SECTION
----

##en_EN

This module is used to create HTML content and insert it to your template with loops.

## Installation

* Copy the module into ```<thelia_root>/local/modules/``` directory
* Be sure that the name of the module is Section.
* Activate it in your thelia administration panel.

## HOW TO USE

Go the thelia administration panel, Then click on "configure" to manage the section.

To add the HTML content of a section, add this loop to your template
```html
{loop name="section_footer" type="section" id=1}
    {$DESCRIPTION nofilter}
{/loop}
```


##fr_FR

Ce module vous permet de créer des contenus HTML et de les afficher dans votre template via des boucles.

## Installation

* Copiez le module dans le répertoire <thelia_root>/local/modules/.
* Vérifiez bien que le nom du module soit Section.
* Activez le module dans l'interface d'administration.

## Utilisation 

Dans l'interface d'administration des modules, cliquer sur "configurer" pour gérer les sections.

Pour ajouter le contenu d'une section, ajouter cette boucle dans votre template
```html
{loop name="section_footer" type="section" id=1}
    {$DESCRIPTION nofilter}
{/loop}
```

