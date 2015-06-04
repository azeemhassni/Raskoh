# Raskoh - WP PostType and Taxonomies

[![Latest Stable Version](https://poser.pugx.org/azi/raskoh/v/stable)](https://packagist.org/packages/azi/raskoh) [![Total Downloads](https://poser.pugx.org/azi/raskoh/downloads)](https://packagist.org/packages/azi/raskoh) [![Latest Unstable Version](https://poser.pugx.org/azi/raskoh/v/unstable)](https://packagist.org/packages/azi/raskoh) [![License](https://poser.pugx.org/azi/raskoh/license)](https://packagist.org/packages/azi/raskoh)

Registring custom post types and taxonomies in wordpress is not a headache anymore. Raskoh will make your life simpler.

![Usage in theme functions.php](https://raw.githubusercontent.com/azeemhassni/Raskoh/master/code-capture.PNG)

#Install
You can insall Raskoh as wordpress plugin by downloading the package and pulling it in `wp-content/plugins` folder or
using composer.

Paste this in `composer.json` file
```json
{
   "require" : {
        "azi/raskoh" : "0.*"
   }
}
```

or just run this command in your project.
`$ composer require azi/raskoh`

include composers autoloader in you themes `functions.php` 
```php 
   require_once "vendor/autoloader.php";
```

#Usage
##### Register a Post Types
to register a post type
```php
   $event = new Raskoh\PostType("Music")->register();
```
##### Add a Taxonomy
register a taxonomy along with post type
```php
   $event = Raskoh\PostType::getInstance("Music");
   $event->taxonomy('Singer')->register();
```

##### Register Multiple Taxonomies
```php
   $event = Raskoh\PostType::getInstance("Music");
   $event->taxonomy(['singer','genre'])->register();
```

##### Set Icons
you can also set icons to your post type 
```php
   $event = Raskoh\PostType::getInstance("Music");
   $event->taxonomy('Singer')->setIcon('dashicons-format-audioy')->register();
```



you can pass all other arguments listed at Codex for `wp_register_post_type()` like this
```php
   $event = new Raskoh\PostType::getInstance();
   $event->set{ArgumentName}
```

