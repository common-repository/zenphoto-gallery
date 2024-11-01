<?php
/*
Plugin Name: Zenphoto Gallery
Plugin URI: http://lmazy.verrech.net/zenphoto-gallery/
Description: This plugin uses Zenphoto to include image galleries in Wordpress posts without database or local file access to Zenphoto.
Version: 2.1.4
Author: Raphael Reitzig
Author URI: http://lmazy.verrech.net/
License: GPL2
*/
?>
<?php
/*  Copyright 2011 Raphael Reitzig (wordpress@verrech.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
  // Register Shortcodes
  add_shortcode('zenimage', 'zpg_insert_image');
  add_shortcode('zenalbum', 'zpg_insert_album');
  add_shortcode('zenlatest', 'zpg_insert_latest');
  add_shortcode('zengallery', 'zpg_insert_gallery');

  /* Register Options
   * We have the following settings:
   * zpg_url            Zenphoto URL
   *
   * zpg_link           Sets default behaviour for image links. Has to be
   *                    one of none, page, album, image, main. Can be overridden
   *                    with shortcode option.
   * zpg_title          Sets default behaviour for image titles. Has to
   *                    be one of none, name, description.
   * zpg_caption        Sets default behaviour for image captions. Has to
   *                    be one of none, name, description.
   * zbg_choice         Show the feed's latest, earliest or random images
   * zpg_nr_row         Maximum number of images to show per row
   * zpg_clip           If true, images will be clipped according to
   *                    zpg_clip_w and zpg_clip_h
   * zpg_clip_w         Thumbnail width if clipping is on
   * zpg_clip_h         Thumbnail height if clipping is on
   * zpg_width          Maximum width for images if clipping is off
   *
   * zpg_single_link    Sets default behaviour for image links. Has to be
   *                    one of none, page, album, image, main. Can be overridden
   *                    with shortcode option.
   * zpg_single_title   Sets default behaviour for image titles. Has to
   *                    be one of none, name, description.
   * zpg_single_caption Sets default behaviour for image captions. Has to
   *                    be one of none, name, description.
   * zpg_single_clip    If true, images will be clipped according to
   *                    zpg_clip_w and zpg_clip_h
   * zpg_single_clip_w  Thumbnail width if clipping is on
   * zpg_single_clip_h  Thumbnail height if clipping is on
   * zpg_single_width   Maximum width for images if clipping is off
   *
   * zpg_class_div      Sets class for gallery enclosing div
   * zpg_style_div      Sets style information for gallery enclosing div
   * zpg_class_idiv     Sets class for image enclosing div
   * zpg_style_idiv     Sets style information for image enclosing div
   * zpg_class_link     Sets class for image links
   * zpg_style_link     Sets style information for image links
   * zpg_rel_link       Sets rel attribute for image links
   * zpg_class_image    Sets class for images
   * zpg_style_image    Sets style information for images
   * zpg_class_caption  Sets class for image captions
   * zpg_style_caption  Sets style information for image captions
   */

  // Register uninstall hook
  register_uninstall_hook(__FILE__, 'zenphoto_gallery_deinstall');
  function zenphoto_gallery_deinstall() {
    delete_option('zenphoto_gallery');
  }

  if ( is_admin() ) {
    // Register Options Page
    add_action('admin_menu', 'zpg_admin_menu_init');
    // Register settings
    add_action('admin_init', 'setup_settings');
    // Add link to options page to plugin list
    add_filter('plugin_action_links', 'zpg_plugin_list_link', 10, 2);
  }

  function zpg_admin_menu_init() {
    add_options_page('Zenphoto Gallery Options', 'Zenphoto Gallery', 'manage_options', basename(__FILE__), 'zpg_options_page');
  }

  function setup_settings() {
    register_setting('zenphoto_gallery', 'zenphoto_gallery', 'zpg_options_validate');
  }

  function zpg_plugin_list_link( $links, $file ) {
    static $this_plugin;
    if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

    if ( $file == $this_plugin ) {
      $settings_link = '<a href="options-general.php?page='.basename(__FILE__).'">Settings</a>';
      array_unshift( $links, $settings_link );
    }
    return $links;
  }

  // This function prints the option page.
  function zpg_options_page() {
    $options = get_option('zenphoto_gallery');
    include('settings.inc.php');
    include('options_page.inc.php');
  }

  // Add tab to media dialog
  add_filter('media_upload_tabs', 'zpg_add_media_tab');
  function zpg_add_media_tab($tabs) {
    $tabs['zenphoto-gallery'] = 'From Zenphoto';
    asort($tabs);
    return $tabs;
  }

  // Add handler for new media tab
  function media_zenphoto_gallery_tab() {
    media_upload_header();
    include('dialog.inc.php');
  }
  function zpg_handle_media_tab() {
    wp_enqueue_script('zpg_dialog_script', plugins_url('', __FILE__).'/dialog.js', array('jquery'), '1.0');

    wp_enqueue_style('zpg_dialog_style',plugins_url('', __FILE__).'/dialog.css');
    wp_enqueue_style('zpg_gallery_style_default', plugins_url('', __FILE__).'/style.css.php');
    return wp_iframe('media_zenphoto_gallery_tab');
  }
  add_action('media_upload_zenphoto-gallery', 'zpg_handle_media_tab');

  // Add handler for shortcode creator preview ajax calls
  add_action('wp_ajax_zpg_preview', 'zpg_ajax_preview');
  function zpg_ajax_preview() {
    echo do_shortcode($_POST['text']);
    die(); // this is required to return a proper result
  }

  /**
   *  This function validates all option input.
   */
  function zpg_options_validate($input) {
    $options = get_option('zenphoto_gallery');

    // We need to have an URL as URL, right?
    if ( !empty($input['zpg_url']) ) {
      $tmp = trim($input['zpg_url']);
      if ( preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $tmp) ) {
        $options['zpg_url'] = $tmp;
      }
    }

    // Number of images per row has to be numerical
    if ( !empty($input['zpg_nr_row']) ) {
      $tmp = trim($input['zpg_nr_row']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_nr_row'] = $tmp;
      }
    }

    // Boolean indicates wether clipping or not
    if ( !empty($input['zpg_clip']) ) {
      $tmp = $input['zpg_clip'];
      if ( $tmp === "true" ) {
        $options['zpg_clip'] = "true";
      }
      else {
        $options['zpg_clip'] = "false";
      }
    }

    // Clip width is numerical
    if ( !empty($input['zpg_clip_w']) ) {
      $tmp = trim($input['zpg_clip_w']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_clip_w'] = $tmp;
      }
    }

    // Clip height is numerical
    if ( !empty($input['zpg_clip_h']) ) {
      $tmp = trim($input['zpg_clip_h']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_clip_h'] = $tmp;
      }
    }

    // Maximum width is numerical
    if ( !empty($input['zpg_width']) ) {
      $tmp = trim($input['zpg_width']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_width'] = $tmp;
      }
    }

    // Link behaviour has a fixed range
    if ( !empty($input['zpg_link']) ) {
      $tmp = trim($input['zpg_link']);
      if ( in_array($tmp, array("none", "page", "album", "image", "main")) ) {
        $options['zpg_link'] = $tmp;
      }
      else {
        $options['zpg_link'] = "page";
      }
    }

    // Title behaviour has a fixed range
    if ( !empty($input['zpg_title']) ) {
      $tmp = trim($input['zpg_title']);
      if ( in_array($tmp, array("none", "name", "desc")) ) {
        $options['zpg_title'] = $tmp;
      }
      else {
        $options['zpg_title'] = "name";
      }
    }

    // Caption behaviour has a fixed range
    if ( !empty($input['zpg_caption']) ) {
      $tmp = trim($input['zpg_caption']);
      if ( in_array($tmp, array("none", "name", "desc")) ) {
        $options['zpg_caption'] = $tmp;
      }
      else {
        $options['zpg_caption'] = "name";
      }
    }

    // Boolean indicates wether clipping or not
    if ( !empty($input['zpg_single_clip']) ) {
      $tmp = $input['zpg_single_clip'];
      if ( $tmp === "true" ) {
        $options['zpg_single_clip'] = "true";
      }
      else {
        $options['zpg_single_clip'] = "false";
      }
    }

    // Clip width is numerical
    if ( !empty($input['zpg_single_clip_w']) ) {
      $tmp = trim($input['zpg_single_clip_w']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_single_clip_w'] = $tmp;
      }
    }

    // Clip height is numerical
    if ( !empty($input['zpg_single_clip_h']) ) {
      $tmp = trim($input['zpg_single_clip_h']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_single_clip_h'] = $tmp;
      }
    }

    // Maximum width is numerical
    if ( !empty($input['zpg_single_width']) ) {
      $tmp = trim($input['zpg_single_width']);
      if ( preg_match('|^[0-9]+$|i', $tmp) ) {
        $options['zpg_single_width'] = $tmp;
      }
    }

    // Link behaviour has a fixed range
    if ( !empty($input['zpg_single_link']) ) {
      $tmp = trim($input['zpg_single_link']);
      if ( in_array($tmp, array("none", "page", "album", "image", "main")) ) {
        $options['zpg_single_link'] = $tmp;
      }
      else {
        $options['zpg_single_link'] = "page";
      }
    }

    // Title behaviour has a fixed range
    if ( !empty($input['zpg_single_title']) ) {
      $tmp = trim($input['zpg_single_title']);
      if ( in_array($tmp, array("none", "name", "desc")) ) {
        $options['zpg_single_title'] = $tmp;
      }
      else {
        $options['zpg_single_title'] = "name";
      }
    }

    // Caption behaviour has a fixed range
    if ( !empty($input['zpg_single_caption']) ) {
      $tmp = trim($input['zpg_single_caption']);
      if ( in_array($tmp, array("none", "name", "desc")) ) {
        $options['zpg_single_caption'] = $tmp;
      }
      else {
        $options['zpg_single_caption'] = "name";
      }
    }

    // Image choice has a fixed range
    if ( !empty($input['zpg_choice']) ) {
      $tmp = trim($input['zpg_choice']);
      if ( in_array($tmp, array("latest", "earliest", "random")) ) {
        $options['zpg_choice'] = $tmp;
      }
      else {
        $options['zpg_choice'] = "latest";
      }
    }

    //@TODO include sanitizing if necessary
    $options['zpg_class_div'] = empty($input['zpg_class_div']) ? '' : trim($input['zpg_class_div']);
    $options['zpg_style_div'] = empty($input['zpg_style_div']) ? '' : trim($input['zpg_style_div']);
    $options['zpg_class_idiv'] = empty($input['zpg_class_idiv']) ? '' : trim($input['zpg_class_idiv']);
    $options['zpg_style_idiv'] = empty($input['zpg_style_idiv']) ? '' : trim($input['zpg_style_idiv']);
    $options['zpg_class_link'] = empty($input['zpg_class_link']) ? '' : trim($input['zpg_class_link']);
    $options['zpg_style_link'] = empty($input['zpg_style_link']) ? '' : trim($input['zpg_style_link']);
    $options['zpg_rel_link'] = empty($input['zpg_rel_link']) ? '' : trim($input['zpg_rel_link']);
    $options['zpg_class_image'] = empty($input['zpg_class_image']) ? '' : trim($input['zpg_class_image']);
    $options['zpg_style_image'] = empty($input['zpg_style_image']) ? '' : trim($input['zpg_style_image']);
    $options['zpg_class_caption'] = empty($input['zpg_class_caption']) ? '' : trim($input['zpg_class_caption']);
    $options['zpg_style_caption'] = empty($input['zpg_style_caption']) ? '' : trim($input['zpg_style_caption']);

    return $options;
  }

  /**
   * This function overloads the defaults set by the user in
   * administration with the parameters he passes the shortcodes.
   * The entered data are validated in the same way as for setup
   * input. The function returns an option array or a string
   * with an error message.
   * @param array param Array with shortcode parameters
   * @param boolean gallery Boolean indicated what options shall
   *    be overridden. Pass false to override single image settings.
   *    Default is true.
   */
  function zpg_overload_options($param, $gallery=true) {
    $delta = get_option('zenphoto_gallery');

    $s = '';
    if ( !$gallery ) {
      $s = 'single_';
    }

    // For both galleries and single pictures
    if ( !empty($param['link']) ) {
      $delta['zpg_'.$s.'link'] = $param['link'];
    }
    if ( !empty($param['title']) ) {
      $delta['zpg_'.$s.'title'] = $param['title'];
    }
    if ( !empty($param['caption']) ) {
      $delta['zpg_'.$s.'caption'] = $param['caption'];
    }
    // If both width and clip are overloaded, prefer width
    if ( !empty($param['width']) ) {
      $delta['zpg_'.$s.'clip'] = "false";
      $delta['zpg_'.$s.'width'] = $param['width'];
    }
    elseif ( !empty($param['clip']) ) {
      $dim = explode("x", $param['clip']);

      if ( count($dim) !== 2 ) {
        return '<div class="errorbox">Enter clip dimensions in pixels like this: WxH (e.g. 250x100)</div>';
      }

      $delta['zpg_'.$s.'clip'] = "true";
      $delta['zpg_'.$s.'clip_w'] = $dim[0];
      $delta['zpg_'.$s.'clip_h'] = $dim[1];
    }

    // Only for galleries; silent ignore when used on pictures
    if ( !empty($param['shown']) ) {
      $delta['zpg_choice'] = $param['shown'];
    }
    if ( !empty($param['row']) ) {
      $delta['zpg_nr_row'] = $param['row'];
    }

    return zpg_options_validate($delta);
  }

  /**
   * This function is invoked by the shorttag [zenalbum] and prints
   * a gallery with the latest images in a given album according to the
   * set Zenphoto's album RSS feed.
   * @param array $param Array containing options. May not be null, can be
   *   empty.
   *   Optional parameter is 'number' (integer). If 'number' is not given,
   *   all images are shown.
   *   Furthermore, optional parameters are 'link', 'title', 'caption',
   *   'clip', 'width','row','shown', overriding default settings.
   */
  function zpg_insert_album($param) {
    $options = zpg_overload_options($param);
    if ( !is_array($options) ) {
      return $options;
    }

    if ( empty($options['zpg_url']) ) {
      return '<div class="errorbox">Please configure Zenphoto Gallery.</div>';
    }

    if ( empty($param['album']) ) {
      return '<div class="errorbox">You need to specify a parameter album, e.g. [zenalbum album=my-album]</div>';
    }

    if ( !zpg_check_json() ) {
      return '<div class="errorbox">Error retrieving images. See configuration for details.</div>';
    }

    $json = file_get_contents($options['zpg_url'].'/json.php?folder='.$param['album']);
    $images = json_decode($json, TRUE);

    require_once('gallery.inc.php');

    return zpg_create_gallery($param, $options, $images);
  }

  /**
   * This function is invoked by the shorttag [zenlatest] and prints
   * a gallery with the latest images according to the set Zenphoto's
   * RSS feed.
   * @param array $param Array containing options. May not be null.
   *   Must contain 'album' (string) specifying the album that is to be
   *   shown.
   *   Optional parameter is 'number' (integer). If 'number' is not given,
   *   all images are shown.
   *   Furthermore, optional parameters are 'link', 'title', 'caption',
   *   'clip', 'width','row','shown', overriding default settings.
   */
  function zpg_insert_latest($param) {
    $options = zpg_overload_options($param);
    if ( !is_array($options) ) {
      return $options;
    }

    if ( empty($options['zpg_url']) ) {
      return '<div class="errorbox">Please configure Zenphoto Gallery.</div>';
    }

    if ( !zpg_check_json() ) {
      return '<div class="errorbox">Error retrieving images. See configuration for details.</div>';
    }

    $json = file_get_contents($options['zpg_url'].'/json.php');
    $images = json_decode($json, TRUE);

    require_once('gallery.inc.php');

    return zpg_create_gallery($param, $options, $images);
  }

  /**
   * This function is invoked by the shorttag [zengallery] and prints
   * a gallery with the images specified in the shortcode's content.
   * @param array $param Array containing options. May not be null.
   *   Optional parameters are 'number' (integer) and 'album'. If 'number'
   *   is not given, all images are shown. If 'album' is set, its value
   *   will be used for all specified images. Specification must not
   *   contain album values in this case.
   *   Furthermore, optional parameters are 'link', 'title', 'caption',
   *   'clip', 'width','row','shown', overriding default settings.
   */
  function zpg_insert_gallery($param, $content=array()) {
    $options = zpg_overload_options($param);
    if ( !is_array($options) ) {
      return $options;
    }

    if ( empty($options['zpg_url']) ) {
      return '<div class="errorbox">Please configure Zenphoto Gallery.</div>';
    }

    if ( empty($content) ) {
      return '<div class="errorbox">Please specify some images, e.g. like
              [zengallery]album1,img1.jpg;album2,img2.jpg[/zengallery]</div>';
    }

    $src = explode(";", $content);
    $images = array();
    $base = empty($param['album']) ? 1 : 0;

    $width = "250";
    if ( $options['zpg_clip'] == "false" && (int)$options['zpg_width'] > 250 ) {
      $width = $options['zpg_width'];
    }

    foreach ( $src as $img ) {
      /* Extract image information; description may contain commas,
       * therefore setting exploding limit. */
      $img = explode(",", $img, 3+$base);
      if ( count($img) >= (1+$base) ) {
        $album = trim(strip_tags(empty($param['album']) ? $img[0] : $param['album']));
        $img[$base] = trim(strip_tags($img[$base]));
        $images[] = array("title" => !empty($img[$base+1]) ? strip_tags($img[$base+1]) : '',
                          "album" => $album,
                          "file" => $img[$base],
                          "thumb" => $options['zpg_url'].'/zp-core/i.php?a='.$album.'&amp;i='.$img[$base].'&w='.$width.'px',
                          "description" => !empty($img[$base+2]) ? $img[$base+2] : '');
      }
    }

    require_once('gallery.inc.php');

    return zpg_create_gallery($param, $options, $images);
  }

  /**
   * This function is invoked by the shortcode [zenimage] and returns
   * HTML code that shows the image specified in the shortcode's
   * parameters.
   * @param array $param Array containing parameters. May not be null.
   * Must contain 'album' and 'image', optional are 'name', 'desc', 'link',
   * 'title', 'caption', 'clip', 'width'.
   */
  function zpg_insert_image($param) {
    $options = zpg_overload_options($param, false);
    if ( !is_array($options) ) {
      return $options;
    }

    if ( empty($options['zpg_url']) ) {
      return '<div class="errorbox">Please configure Zenphoto Gallery.</div>';
    }

    if ( empty($param['album']) || empty($param['image'] ) ) {
      return '<div class="errorbox">Please specify album and image, e.g. like
              [zenimage album=name image=file.jpg]</div>';
    }

    require_once('gallery.inc.php');

    return zpg_create_image($options, array("title" => !empty($param['name']) ? $param['name'] : '',
                          "album" => $param['album'],
                          "file" => $param['image'],
                          "thumb" => $options['zpg_url'].'/zp-core/i.php?a='.$param['album'].'&amp;i='.$param['image'].'&amp;w='.$options['zpg_single_width'].'px',
                          "description" => !empty($param['desc']) ? $param['desc'] : ''));
  }

  /**
   * This function checks wether images can be retrieved with the current
   * settings
   * @returns true if and only if data can be retrieved */
  function zpg_check_json() {
    $options = get_option('zenphoto_gallery');

    $success = @fopen($options['zpg_url'].'/json.php','r');
    @fclose($success);
    return (bool)$success;
  }
  ?>
