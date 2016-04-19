=== Raskoh ===
Contributors: azibaloch
Tested up to: 4.5
License: MIT

Easy and object oriented way to interact with WordPress custom post types and taxonomies

== Description ==
Registering custom post types and taxonomies in WordPress is not a headache anymore. Raskoh will make your life simpler.

=== Usage ===

Register a Post Types

to register a post type

 
$music = new Raskoh\\PostType(\"Music\");
$music->register();


Add a Taxonomy

register a taxonomy along with post type


$music = Raskoh\\PostType::getInstance(\"Music\");
$music->taxonomy(\'Singer\')->register();


Restrict Posts by Term

if you want to add Terms dropdown on WordPress admin interface to restrict posts by terms. just pass a second boolean to php PostType::taxonomy($name, $filters = false) method.


$music = Raskoh\\PostType::getInstance(\"Music\");
$music->taxonomy(\'Singer\', true)->register();


Register Multiple Taxonomies


$music = Raskoh\\PostType::getInstance(\"Music\");
$music->taxonomy([\'singer\',\'genre\'])->register();


Set Icons

you can also set icons to your post type


$music = Raskoh\\PostType::getInstance(\"Music\");
$music->taxonomy(\'Singer\')->setIcon(\'dashicons-format-audioy\')->register();


you can pass all other arguments listed at Codex for wp_register_post_type() like this


$CPT = Raskoh\\PostType::getInstance();
$CPT->set{ArgumentName}


== Installation ==
You can insall Raskoh as wordpress plugin by downloading the package and pulling it in wp-content/plugins folder or using composer.

Paste this in `composer.json` file


{
   \"require\" : {
        \"azi/raskoh\" : \"1.*\"
   }
}


or just run this command in your project. `$ composer require azi/raskoh`

include composers autoloader in you themes `functions.php`

require_once \"vendor/autoloader.php\";

Or just download the package and move the files to `wp-content/themes` directory and activate it from your admin screen.

That\'s it.
