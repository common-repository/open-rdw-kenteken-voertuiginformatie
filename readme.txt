# Tussendoor - Open RDW
Contributors: Tussendoor
Tags: tussendoor, rdw, kenteken, voertuig, kentekeninformatie
Stable tag: 5.1.3
Tested up to: 6.6.1
Requires at least: 6.2
Requires PHP: 8.1
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Haal kenteken / voertuig informatie op van OpenRDW met de Open RDW Kenteken plugin.

## Description
Met de Open RDW Kenteken plugin van [Tussendoor](https://www.tussendoor.nl), haal je op een eenvoudige wijze kenteken en voertuig informatie op via de gratis dienst van de RDW ([Open RDW](https://www.rdw.nl/))


### Toepassingen

De data die opgehaald wordt kun je gebruiken bij diverse toepassingen binnen je WordPress site. Denk hierbij aan:

*   Koppelingen met webwinkel software (onderdelen per merk / model);
*   Formulieren voor het inruilen van een auto;
*   Inplannen van afspraken in een werkplaats.


En zo zijn er nog legio mogelijkheden te bedenken.

### Integratie

De plugin beschikt over een aantal standaard WordPress integraties. Zo kun je de data ophalen via:

*   ContactForm 7;
*   GravityForms;
*   Widgets;
*   Classic WordPress Editor (TinyMCE) (No active support);
*   Widgets (No active support);
*   Shortcodes via een eenvoudig selectie menu in de WYSIWYG editor.

###  Installatie

1. Upload de plugin naar je WordPress site. Dit kan handmatig (via FTP) en via het WordPress dashboard (als *.zip bestand).
2. Activeer de plugin onder de plugin pagina.
3. Voer de licentie in die je van Tussendoor hebt ontvangen.
4. Ga naar Widgets, pagina / berichten beheer of formulier builder om je eerste kentekencontrole in te bouwen en direct gratis te gebruiken.

### Changelog

##### 5.1.3
* Updated: We have added more fields.
* Updated: New categories for new fields.
* Updated: Translations in English and Dutch.
* Updated: Do not show certain notices anymore with a valid license.
* Added: Support for vehicles with multiple fuel types (hybrid vehicles).
* Added: Formatting of datetime items to (DD-MM-YYYY).
* Added: Usage stats for the current week.

##### 5.1.2
* Fixed: TinyMCE fired on init causing Headers already sent error.

##### 5.1.1
* Fixed: Visual bug where license looked invalid while it was not.
* Fixed: TinyMCE modal did not render content.
* Fixed: JavaScript for some modals was not loaded poperly.
* Fixed: Older CSS styles not were missing/ not loaded in form generator popup.

##### 5.1.0.1
* Hotfix: Recurring requests without a page refresh failed with CF7.

##### 5.1.0
* Fixed:  An instance where jQuery was not triggered because it was loaded before DOM ready.
* Fixed:  An jQuery issue where some CF7 requests where not working.
* Fixed:  An issue where some CF7 forms had no ID in the HTML.
* Added:  The option to manually get a new API token from Tussendoor if no data is returned.

##### 5.0.5.1
* Hotfix:  Better JS checking to prevent errors.

##### 5.0.5
* Fixed: Check whether dateTime is compatible with Carbon Parse.
* Removed: Older styles where enqueued.
* Updated: Stats now use v2 enpoints.
* Updated: Stability improvements on activating plugin with license.
* Updated: Stability improvements in JavaScript.
* Updated: Readme.md.

##### 5.0.4.8
* Updated: Replaced wrong texts.

##### 5.0.4.7
* Updated: Replaced wrong texts.

##### 5.0.4.6
* Updated: Replaced wrong texts.

##### 5.0.4.6
* Hotfix: Namespacing failures from Composer.

##### 5.0.4.5
* Hotfix: Composer giving fatal error.

##### 5.0.4.4
* Hotfix: Inital Kerel.php lookup fail fix.

##### 5.0.4.3
* Hotfix: Better JS checking in admin dashboard to prevent errors.

##### 5.0.4.2
* Hotfix: Better type checking in admin dashboard to prevent errors.
* Update: Add licence back to readme.txt.

##### 5.0.4.1
* Updated: More stable Dashborad JS.
* Updated: renamed plugin file to old name 'plugin-gratis-open-rdw-kenteken-voertuiginformatie.php'.

##### 5.0.4
* Updated: Cleaned up files.
* Fixed: Dutch translations.

##### 5.0.3.1
* Fixed: Small namespace issue.

##### 5.0.3
* Updated: Controllers to handle data differently on API V2 new endpoints.

##### 5.0.2
* Fixed: Moved plugin config to make sure all assets are loaded properly over HTTPS.

##### 5.0.1
* Added: V2 API endpoints for data handeling.
* Fixed: Gracefully handle errors on Statisics tab.

##### 5.0.0
* Updated: We put our time and effort in to bringing the plugin into new standards of programming.
* Updated: We have rewriten the core and made sure to maintain backwards compatibility.

##### 2.2.8
* Bugfix: Fixed an issue occuring on update.

##### 2.2.7
* Tweak: escaped public data before adding it to HTML

##### 2.2.6
* Updated: Plugin update checker

##### 2.2.5
* Fixed: Fatal error when Gravity Forms is not used
* Updated: CSS support for Gravity Forms kenteken field

##### 2.2.4
* Updated: GravityForms support
* Updated: WordPress 6.0 support

##### 2.2.3
* Updated: Removed deprecated jQuery function
* Updated: Missing CF7 field validation

##### 2.2.2
* Updated: WordPress 5.4 support

##### 2.2.1
* Fixed: An issue with QuForm
* Added: Filters before and after getting data from the API

##### 2.2.0
* Added: Output formatters to change the default display of fields in a certain way
* Added: Default output formatters for dates
* Added: Default output formatters for money
* Added: Default output formatters for strings
* Added: Default output formatters for developers (timestamps and callbacks)

##### 2.1.6
* Fixed: An issue with WooCommerce compatability

##### 2.1.5
* Fixed: Minor bug fixes

##### 2.1.4
* Added: Tabindex attribute for the Gravity Forms fields.
* Fixed: The fields select for Gravity Forms is now sorted alphabetically.

##### 2.1.3
* Fixed: A bug that affected the plugin installation screen

##### 2.1.2
* Fixed: Replaced deprecated function (Contact Form 7)
* Fixed: Small bugs

##### 2.1.1
* Improved: We've updated some internal components making this the fastest version yet! :D

##### 2.1.0
* Added: Logging capabilities for debugging
* Added: Extensions
* Improved: Stability

##### 2.0.3
* Fixed: Plugin Update Checker

##### 2.0.2
* Added: Gravity Forms support

##### 2.0.1
* Fixed: Squashed some bugs!
* Improved: Stability

##### 2.0.0
* Improved: Complete rewrite of the plugin
* Improved: Better integration with Contact Form 7
* Improved: Switched API calls from XML to JSON, significantly improving the loading speed.
* Improved: Usability with Contact Form 7
* Added: Introduction screen to the plugin
* Added: Easy settings management
* Added: Easier management of shortcode's
* Added: Easier placement of widget
* Added: Multiple selection of fields
* Added: Order information fields within the widget/shortcode or CF7
* Added: Support for over 80 values per license plate

##### 1.1.0
* Added: Support for new Open data RDW API

##### 1.0.9
* Added: Support for Contact Form 7 version 4.2

##### 1.0.8
* Fixed: Modal not showing in the text editor
* Added: cURL for better handling the API

##### 1.0.7
* Added: Support for WordPress version 4.2
* Added: Support for Contact Form 7 version 4.1.1

##### 1.0.6
* Added: Support for Contact Form 7 version 3.9

##### 1.0.5
* Fixed: Bug QuForm

##### 1.0.4
* Added: QuForm support

##### 1.0.3
* Fixed: Reloading of javascripts

##### 1.0.2
* Fixed: Form with license not working in Firefox, Opera and Safari

##### 1.0.1
* Fixed: widget not showing data

##### 1.0.0
* Initial release

#### Translations

* Dutch / Nederlands - Standaard taal is Nederlands
* English
