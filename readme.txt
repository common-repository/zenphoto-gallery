=== Plugin Name ===
Contributors: akerbos87
Tags: zenphoto, images, gallery
Requires at least: 3.0.0
Tested up to: 3.3
Stable tag: 2.1.4

This plugin uses Zenphoto to include images and galleries in Wordpress posts without database or local file access to Zenphoto.

== Description ==

*Zenphoto Gallery* allows to use *Zenphoto* albums in *WordPress* posts. Other plugins need access to *Zenphoto*'s database and/or require *Zenphoto* to be installed on the same webserver as *WordPress*. Not so this one!

Given a JSON frontend to *Zenphoto*'s RSS facilities (included), *Zenphoto Gallery* pulls the images from both the general images feed and album feeds. That is you can access any *Zenphoto* install anywhere as long as you get its provider to add one file! Another possibility is to provide album and image file names yourself and let *Zenphoto Gallery* put together galleries from them; you can access any *Zenphoto* installation this way.

*Zenphoto Gallery* provides some humble means to customize gallery layout and behaviour. It also features a shortcode creator that integrates with the standard *Wordpress* image adding dialogue and provides a dynamic preview.

Note that *Zenphoto Gallery* is written with the intention in mind to show off some images and lure visitors to your *Zenphoto* site. Newer versions enable you to style most elements of your galleries, though.

Features include:

* Get images from remote *Zenphoto*
* Provides shortcodes for
  * gallery with latest images,
  * album gallery,
  * custom galleries and
  * single images.
* Restrict number of images in one gallery
* Show first, last or random images
* Clip or downscale images to configurable dimensions
* Variable image link target
* Variable image title and caption
* Each gallery is individually configurable
* Shortcode creation dialog with dynamic preview
* Extensive styling options

This plugin is tested and runs with

* Wordpress 3.x
* Zenphoto 1.3
* PHP 5

== Installation ==

1. Install *Zenphoto Gallery* via your blog's plugin administration
2. Copy/Move `zenphoto_gallery/json.php` to your *Zenphoto* root directory
3. Activate the plugin through the 'Plugins' menu in *WordPress*
4. Configure *Zenphoto Gallery* via the 'Settings' menu in *WordPress*

== Frequently Asked Questions ==

= How do I show galleries using Zenphoto Gallery? =
Just use the shortcode creator via the well know image adding dialogue.

You can of course add one of the shortcodes by hand to your posts and pages:

* `[zenalbum album=name]` inserts a gallery for the album named `name`
* `[zenlatest]` inserts a gallery with the latest images (or whatever you configured *Zenphoto* to push to the feed).
* `[zengallery]album1,file1.jpg;album2,file2.jpg[/zengallery]` inserts a gallery containing the specified images.
All shortcodes understand the optional argument `number=k` which limits the number of shown images to `k`. If it is not provided,
all images are shown. So, for example `[zenalbum album=birthday-party number=5]` inserts the first five images that occur in the feed of your birthday album.

Using `[zengallery]`, you can specify image names and descriptions by just adding them to the shortcode's content: `[zengallery]album,image,name,description text[/zengallery]`. While image descriptions may contain commas, the other fields may not. You can use HTML in descriptions if need be. If you want to show only pictures from one album, you might want to set the album for all images: `[zengallery album=albumname]img1;img2[/zengallery]`. Take care to leave out the album name inside the shortcode.

= How do I show single images using Zenphoto Gallery? =
`[zenimage album=name image=file.jpg]` inserts the specified image.

= Can I get more finegrained control? =
Sure. You can override all gallery and image settings via shortcode parameters. That is:

* `link=(page|album|image|none)` sets were links go to,
* `title=(none|name|desc)` sets what is shown as image title,
* `caption=(none|name|desc)` sets what is shown as image caption,
* `width=$int` sets the maximum image width in pixels and
* `clip=WxH` sets the dimensions to clip images to (width and height in pixels).
Note that specifying `width` turns clipping off in any case.

In case of the three gallery shortcodes, you can also use those:

* `shown=(earliest|latest|random)` sets which of the provided images to show if there are more than you have space for and
* `row=$integer` sets the maximum number of images per row.

= What to use as album name? =
You have to use the string *Zenphoto* uses in its URLs, with or without slashes at front or end, e.g. `album/subalbum`

= What to use as image name? =
You also have to use what *Zenphoto* uses in its URLs including file format, e.g. `image.jpg`. Do not include `.php` as *Zenphoto* appends it to image page URLs.

= I see the wrong images! =
I assume you have entered the correct album name. If so, check your RSS settings in *Zenphoto*. You can change image order there. Also, the *Zenphoto Gallery* setting *Shown images* might be set in an unintended way; you can override it using the shortcode parameter `shown` (has to have values `latest`, `earliest` or `random`).

= Images are smaller than I ordered! =
Again, check your RSS settings in *Zenphoto*. You can change the thumbnail size the feeds use there. *Zenphoto Gallery* uses those thumbnails, not the full images, and won't scale up to avoid bad looks.

= What is that rel link attribute good for? =
There are *WordPress* plugins that implement nice image viewers with Javascript. These plugins recognize a link they should handle via the `rel` attribute. For instance, using *Shadowbox* you have to put `shadowbox;player=img;` in the configuration field to have direct image links handled by *Shadowbox*.
In order to find out what to put there for other plugins, just open the source of any post where you know your image viewer plugin works and look at the corresponding links. If it does use something different from `rel`, well, you cannot use it at this time.

= Why that JSON stuff? =
I was too lazy to parse RSS/XML to get the little information I need for this plugin. Since I considered uploading an additional file to your *Zenphoto* installation a minor effort, I saw no problem.
Of course, this prevents you from using the shortcodes `[zenlatest]` and `[zenalbum]` with some *Zenphoto* installation you do not own. Bad luck.

= Does Zenphoto Gallery interfere with other plugins? =
To my best knowledge, no. In particular, *Zenphoto Gallery* uses other shortcodes than other *Zenphoto* related plugins so you can continue using them for your older posts.

If you find incompatibilities, please let me know.

= How can I uninstall the plugin? =
Just deactivate and delete it in plugin management. This will also remove all options from database. If you want to keep them, remove `wp-content/plugins/zenphoto-gallery` manually. Do not forget to delete `json.php` from your *Zenphoto* installation.

= You are no help here, it does not work! =
Please contact me and let me know of your problem.

= Can you implement option X? =
Probably. I wanted to keep the number of options as low as possible while achieving as much flexibility as possible -- and there are already a lot. You can contact me with your wish and if the same wish is repeated often (by different people, mind) or I immediatly like your idea, I might very well do it, yes. In case I don't, feel free to manipulate the files yourself. I hope I wrote somewhat clear code (as clear as PHP/HTML/Javascript can get) and commented sufficiently.

= How can I help? =
You can

* use *Zenphoto Gallery*,
* vote on the *Wordpress* plugin portal for it,
* report bugs and/or propose ideas for improvement [here](http://bugs.verrech.net/thebuggenie/zenphotogallery/issues/open) and
* blog about your experience with it.

== Screenshots ==

1. Gallery with rather small thumbnails.
2. Gallery with larger thumbnails and caption in mutiple rows, all nicely aligned.
3. Somewhat larger thumbnails floating peacefully to the right.
4. Multiple galleries per post are no problem. Album repetition here is due to lack of albums, not plugin design.
5. Single image clipped to fit nicely into text.
6. Shortcode creator with preview feature.
7. Zenphoto URL and gallery options.
8. Image options.
9. Styling options.

== Changelog ==

= 2.1.4 =
* [Bug] Fixes broken settings page.

= 2.1.3 =
* [Bug] Fixes wrong use of Wordpress API that would cause fatal errors unter certain circumstances

= 2.1.2 =
* [Bug] Improves codebase to avoid some unnecessary warnings
* [Bug] Fixes previewer AJAX that broke with WP 3.3

= 2.1.1 =
* [Bug] URLs including '&' now use valid '&amp;'.
* [Bug] Last row in gallery tables is now always closed correctly.

= 2.1 =
* [Bug] Maximum number per row had no default value, possibly leading to divisions by zero
* [Feature] Zenphoto main page now available as image link target
* [Feature] Image containers can now be styled
* [Feature] Images can now be styled
* [Feature] Image captions can now be styled
* [Technical] Styles moved to (dynamic) CSS file
* [Technical] Plugin folder name no longer hardcoded

= 2.0 =
* [Feature] Pictures can now be chosen from album (i.e. its feed) in reverse order and randomly
* [Feature] New shortcode `[zengallery]` for showing arbitrary images (without feed)
* [Feature] New shortcode `[zenimage]` for showing single images (without feed)
* [Feature] All gallery/image settings can now be overloaded by shortcode parameters
* [Feature] Shortcode creator with dynamic preview

= 1.0.1 =
* [Bug] Image caption value is now shown correctly in configuration

= 1.0 =
Initial Release

== Upgrade Notice ==

= 2.1.4 =
* [Bug] Fixes broken settings page.

= 2.1.3 =
* Fixes wrong use of Wordpress API that would cause fatal errors unter certain circumstances

= 2.1.2 =
Fixes compatibility with WP 3.3

= 2.1 =
Minor fixes and extensions.

= 2.0 =
Adding new shortcodes, parameter overloading and GUI.

= 1.0 =
Initial Release

== Credits ==
I kindly thank

* the guys building *Zenphoto*,
* *ZenphotoPress* for being so nasty to me that I got motivated to build an own plugin in the first place,
* a nameless guy for his article about *Wordpress*' settings API and
* Joe Seifi for showing me how to clip images
* Vinh Quoc Nguyen for his tutorial on adding tabs to the media upload dialogue

== Bugs and Development ==
See [here](http://bugs.verrech.net/thebuggenie/zenphotogallery/issues/open).
