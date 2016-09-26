# Raskoh - WP PostType and Taxonomies


Registring custom post types and taxonomies in wordpress is not a headache anymore. Raskoh will make your life simpler.

![Usage in theme functions.php](https://raw.githubusercontent.com/azeemhassni/Raskoh/master/code-capture.PNG)

#Install
You can insall Raskoh as wordpress plugin by downloading the package and pulling it in `wp-content/plugins` folder or
using composer.

Paste this in `composer.json` file
```json
{
   "require" : {
        "azi/raskoh" : "1.*"
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
$music = new Raskoh\PostType("Music");
$music->register();
```
##### Add a Taxonomy
register a taxonomy along with post type
```php
$music = Raskoh\PostType::getInstance("Music");
$music->taxonomy('Singer')->register();
```

##### Restrict Posts by Term
if you want to add Terms dropdown on WordPress admin interface to restrict posts by terms. just pass a second boolean to 
```php PostType::taxonomy($name, $filters = false)```  method.
```php
$music = Raskoh\PostType::getInstance("Music");
$music->taxonomy('Singer', true)->register();
```

##### Register Multiple Taxonomies
```php
$music = Raskoh\PostType::getInstance("Music");
$music->taxonomy(['singer','genre'])->register();
```

##### Set Icons
you can also set icons to your post type 
```php
$music = Raskoh\PostType::getInstance("Music");
$music->taxonomy('Singer')->setIcon('dashicons-format-audioy')->register();
```



you can pass all other arguments listed at Codex for `wp_register_post_type()` like this
```php
$CPT = Raskoh\PostType::getInstance();
$CPT->set{ArgumentName}
```

