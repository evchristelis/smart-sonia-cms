=== GSpeech - Text to speech solution for WordPress ===
Contributors: creative-solutions
Author: creative-solutions
Tags: text to speech, speech, google translate, accessibility, audio
Requires at least: 3.5
Tested up to: 6.0.1
Stable tag: 2.8.1

GSpeech is a text to speech solution which uses Google power and allows to listen any selected text on your site!

== Description ==

[GSpeech](http://creative-solutions.net/wordpress/gspeech) is a  text to speech solution for WordPress. It uses Google power to provide you the best quality of automatic text to speech service. Enjoy!

### Useful Links:
> * [Live Demo](http://creative-solutions.net/wordpress/gspeech/demo)  
> * [Documentation](http://creative-solutions.net/wordpress/gspeech/documentation)  
> * [Contact Us](http://creative-solutions.net/contact-us/)

= Download the latest version =
[Download GSpeech](http://creative-solutions.net/wordpress/gspeech) from our website and enable the following features:

### Features:
* Allows to listen any text from the site.
* Listen to selected text. Speaker will apear, when You select a part of the text.
* Autoplay Feature. [See Demo](http://creative-solutions.net/wordpress/gspeech/demo)
* Feature to set greeting audio for your users. 
* Feature to set different greetings for loged in users.
* Speaking menus. Users can listen menus when they hover them!
* Ability to set custom events!
* More than 50 languages supported by Google!
* Unlimited text to speech!
* Place speaker wherever you want!
* Ability to set custom style and language for each TTS block!
* 40 speaker types!
* Customizable TTS block styles!
* Customizable tooltip styles!
* Live preview in administration panel!

### Support:
Please [Contact Us](http://creative-solutions.net/contact-us) for your questions and support requests!

### Usage - Creating Text to Speech blocks.

* ***Basic structure*** - If you want the speaker to appear after the text, do the following!
`{gspeech}Text to speech{/gspeech}`

* ***Structure with parameters*** - You can specify custom styles and language for each Text to Speech block!
`{gspeech style=2 language=en}Custom text to speech{/gspeech}`

* ***GSpeech structure with all parameters will be***
`{gspeech style=2 language=en autoplay=1 speechtimeout=0 registered=0 selector=anyselector event=anyevent hidespeaker=1}welcome{/gspeech}`

For more details, please read the [Documentation](http://creative-solutions.net/wordpress/gspeech/documentation).

### Support:
Please `use` [GSpeech Forum](http://forum.creative-solutions.net/gspeech-wordpress/) for your questions and support requests!

= Requirements =
You must have [curl](http://php.net/manual/en/book.curl.php) library enabled on your hosting!

== Frequently Asked Questions ==

== Installation ==

1. Unzip files.
2. Upload the entire gspeech folder to the /wp-content/plugins/ directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. You will find 'GSpeech' menu in your WordPress admin panel.
5. Configure settings to your needs.
6. Have fun!

== Changelog ==

= V 2.8.1 - 01/08/2022 =
* Tested with WordPress 6.
* Fixed "update to pro version" alert bug.

= V 2.8.0 - 02/04/2021 =
* important style fix in gspeech.css file, to correct bug with tooltip in last WP version!

= V 2.7.32 - 28/09/2020 =
* style correction. 


= V 2.7.31 - 10/06/2020 =
* Minor fix. 

= V 2.7.3 - 10/06/2020 =
* Important! Fixed functionality for PHP 7.x versions. 

= V 2.7.2 - 15/11/2019 =
* Js corrections!

= V 2.7.1 - 26/06/2017 =
* Style corrections.

= V 2.7.0 - 12/10/2016 =
* Fixed bug!

= V 2.0.1 - 14/08/2015 =
* Fixed issue with no-audio!

== Screenshots == 

1. Blue style customization
2. Red style customization
3. GSpeech styles preview
4. GSpeech Box
