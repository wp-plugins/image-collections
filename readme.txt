=== Image Collections ===
Author URI: http://www.abcfolio.com
Plugin URI: http://abcfolio.com/help/wordpress-plugin-image-collections/
Contributors: abcfolio
Tags: image uploader, upload images, upload, uploader, gallery, galleries, image, images, collections, photo, album, photo albums, photos, picture, pictures
Requires at least: 3.5
Tested up to: 4.0

Stable tag: 1.6.3

License: GPLv2 or later

WordPress image uploader and organizer.

== Description ==

### Image uploader and organizer.

If you want to create group of images and keep them in a separate folder this plugin is for you.

The plugin is intended for WordPress theme and plugin developers who are looking for a better way to work with sets of images.

For working examples of how to use Image Collections in other plugins, see:

* **[Fullscreen Slides](http://abcfolio.com/help/wordpress-plugin-fullscreen-slides/)**
* **[Andora Lightbox](http://wordpress.org/plugins/andora-lightbox/)**

In both cases, Image Collections plugin is used as an image uploader and organizer. You may find the concept of custom image management a familiar one if you ever worked with NexGEN Gallery plugin.

Plugin has an administration interface to handle image upload and management. There is no front-end presentation layer. Custom coding is required to use images in posts or plugins.

Image Collections plugin uses custom tables not linked to WordPress Media Library. Images are stored in individual folders outside the Media Library. Optimized data storage provides excellent performance even with large number of records.

**Collection**: set of images stored in its own folder.

### Features

* Each collection has a name and description
* Each collection stores images in its own folder.
* Each collection has its own set of options.
* Each collection has its own image dimensions.
* Up to two image sizes: large and thumbnail.
* Add title, alt and caption to each of the images.

Image Collections plugin can make management of a large number of images a simple task.

### Integration with other Plugins

For a working example of how to integrate Image Collections with other plugins, see

* **[Fullscreen Slides](http://abcfolio.com/help/wordpress-plugin-fullscreen-slides/)**
* **[Andora Lightbox](http://wordpress.org/plugins/andora-lightbox/)**

### User Guide

Full documentation at [http://abcfolio.com/help/wordpress-plugin-image-collections/](http://abcfolio.com/help/wordpress-plugin-image-collections/).

### Premium Version

[Image Collections Pro](http://abcfolio.com/help/wordpress-plugin-image-collections/) premium version offers these extra features:

* You can extract and save image metadata. Both EXIF and IPTC are saved, including keywords and GPS data.
* Up to 4 image sizes: Large, Medium, Thumbnail and Original.
* Option to skip duplicates during the upload.
* You can custom crop and batch recreate thumbnails.
* You can add title, alt, 4 captions, description, custom link, and other data to each of the images.
* You can make image active/inactive without deleting it.
* You can copy images between collections.
* You can move images between collections.
* Compatible with multisite.
* Premium, One-on-One support.


[Visit our Premium page.](http://abcfolio.com/help/wordpress-plugin-image-collections/)

== Installation ==

1. In your Admin, go to menu Plugins > Add
1. Search for **Image Collections**
1. Click to install
1. Activate the plugin
1. Once activated, it creates menu section Collections in left side column in the admin area.
1. Help & documentation: [http://abcfolio.com/help/wordpress-plugin-image-collections/](http://abcfolio.com/help/wordpress-plugin-image-collections//).

That’s it! Enjoy using the plugin.

== Changelog ==

= 1.6.3 20140 =
* Update: Tested compatibility with WordPress 4.0.
* Fix: Fixed warning: $wpdb->prepare missing %.

= 1.6.2 201404 =
* Change: Changed main menu label to Image Collections

= 1.6.1 20140328 =
* New: Added functions to db-user.
* Fix: Fixed path to db-user.

= 1.6.0 =
* New: Added db-user file.

= 1.5.2 =
* Changes: None. Fixing file versions in repository.

= 1.5.1 20130120 =

* Fix: Removed require wpload.php from modal forms. Replaced with ajax.

= 1.5.0 20130114 =

* First official release.