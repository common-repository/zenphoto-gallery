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
  /* Tell Wordpress what settings to handle and how show them on the options page
   * See zenphoto_gallery.php for a readable settings list.
   */

  add_settings_section('zpg_main', 'Main Settings', 'zpg_main_text', 'zenphoto_gallery');
  function zpg_main_text() {
    echo 'Configure your Zenphoto installation.';
  }
  add_settings_field('zpg_url', 'Zenphoto URL:', 'zpg_setting_url', 'zenphoto_gallery', 'zpg_main');
  function zpg_setting_url() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_url' name='zenphoto_gallery[zpg_url]' size='40' type='text' value='{$options['zpg_url']}' />";
  }

add_settings_section('zpg_org', 'Gallery Options', 'zpg_org_text', 'zenphoto_gallery');
  function zpg_org_text() {
    echo 'Configure default gallery layout. Note that images will either be clipped or width-bounded, not both.';
  }
  add_settings_field('zpg_link', 'Image link:', 'zpg_setting_link', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_link() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_link' name='zenphoto_gallery[zpg_link]''>";
      $selected = $options['zpg_link'] === "page" ? " selected" : "";
      echo "<option value='page'{$selected}> Image page</option>";
      $selected = $options['zpg_link'] === "image" ? " selected" : "";
      echo "<option value='image'{$selected}> Image</option>";
      $selected = $options['zpg_link'] === "album" ? " selected" : "";
      echo "<option value='album'{$selected}> Album</option>";
      $selected = $options['zpg_link'] === "main" ? " selected" : "";
      echo "<option value='main'{$selected}> Main Page</option>";
      $selected = $options['zpg_link'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> No link</option>";
    echo "</select>";
  }
  add_settings_field('zpg_title', 'Image title:', 'zpg_setting_title', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_title() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_title' name='zenphoto_gallery[zpg_title]''>";
      $selected = $options['zpg_title'] === "name" ? " selected" : "";
      echo "<option value='name'{$selected}> Image name</option>";
      $selected = $options['zpg_title'] === "desc" ? " selected" : "";
      echo "<option value='desc'{$selected}> Image description</option>";
      $selected = $options['zpg_title'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> Nothing</option>";
    echo "</select>";
  }
  add_settings_field('zpg_caption', 'Image caption:', 'zpg_setting_caption', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_caption() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_caption' name='zenphoto_gallery[zpg_caption]''>";
      $selected = $options['zpg_caption'] === "name" ? " selected" : "";
      echo "<option value='name'{$selected}> Image name</option>";
      $selected = $options['zpg_caption'] === "desc" ? " selected" : "";
      echo "<option value='desc'{$selected}> Image description</option>";
      $selected = $options['zpg_caption'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> Nothing</option>";
    echo "</select>";
  }
  add_settings_field('zpg_choice', 'Shown images:', 'zpg_setting_choice', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_choice() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_choice' name='zenphoto_gallery[zpg_choice]'>";
      $selected = $options['zpg_choice'] === "latest" ? " selected" : "";
      echo "<option value='latest'{$selected}>Latest</option>";
      $selected = $options['zpg_choice'] === "earliest" ? " selected" : "";
      echo "<option value='earliest'{$selected}>Earliest</option>";
      $selected = $options['zpg_choice'] === "random" ? " selected" : "";
      echo "<option value='random'{$selected}>Random</option>";
    echo "</select>";
  }
  add_settings_field('zpg_nr_row', 'Max images per row:', 'zpg_setting_nr_row', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_nr_row() {
    $options = get_option('zenphoto_gallery');
    $options['zpg_nr_row'] = empty($options['zpg_nr_row']) ? 1 :  $options['zpg_nr_row'];
    echo "<input id='zpg_nr_row' name='zenphoto_gallery[zpg_nr_row]' size='5' type='text' value='{$options['zpg_nr_row']}' />";
  }
  add_settings_field('zpg_clip', 'Clip images:', 'zpg_setting_clip', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_clip() {
    $options = get_option('zenphoto_gallery');
    $checked = $options['zpg_clip'] === "true" ? " checked='yes'" : "";
    echo "<input id='zpg_clip' name='zenphoto_gallery[zpg_clip]' type='checkbox' value='true'{$checked} /> <small>Check to have thumbnails cropped to the dimensions given below. Otherwise, they will be scaled to the width given below.</small>";
  }
  add_settings_field('zpg_clip_w', 'Clip width:', 'zpg_setting_clip_w', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_clip_w() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_clip_w' name='zenphoto_gallery[zpg_clip_w]' size='5' type='text' value='{$options['zpg_clip_w']}' />px";
  }
  add_settings_field('zpg_clip_h', 'Clip height:', 'zpg_setting_clip_h', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_clip_h() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_clip_h' name='zenphoto_gallery[zpg_clip_h]' size='5' type='text' value='{$options['zpg_clip_h']}' />px";
  }
  add_settings_field('zpg_width', 'Max image width:', 'zpg_setting_width', 'zenphoto_gallery', 'zpg_org');
  function zpg_setting_width() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_width' name='zenphoto_gallery[zpg_width]' size='5' type='text' value='{$options['zpg_width']}' />px";
  }

add_settings_section('zpg_single', 'Image Options', 'zpg_single_text', 'zenphoto_gallery');
  function zpg_single_text() {
    echo 'Configure default ingle image layout. Note that images will either be clipped or width-bounded, not both.';
  }
  add_settings_field('zpg_single_link', 'Image link:', 'zpg_setting_single_link', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_link() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_single_link' name='zenphoto_gallery[zpg_single_link]''>";
      $selected = $options['zpg_single_link'] === "page" ? " selected" : "";
      echo "<option value='page'{$selected}> Image page</option>";
      $selected = $options['zpg_single_link'] === "image" ? " selected" : "";
      echo "<option value='image'{$selected}> Image</option>";
      $selected = $options['zpg_single_link'] === "album" ? " selected" : "";
      echo "<option value='album'{$selected}> Album</option>";
      $selected = $options['zpg_single_link'] === "main" ? " selected" : "";
      echo "<option value='main'{$selected}> Main Page</option>";
      $selected = $options['zpg_single_link'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> No link</option>";
    echo "</select>";
  }
  add_settings_field('zpg_single_title', 'Image title:', 'zpg_setting_single_title', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_title() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_single_title' name='zenphoto_gallery[zpg_single_title]''>";
      $selected = $options['zpg_single_title'] === "name" ? " selected" : "";
      echo "<option value='name'{$selected}> Image name</option>";
      $selected = $options['zpg_single_title'] === "desc" ? " selected" : "";
      echo "<option value='desc'{$selected}> Image description</option>";
      $selected = $options['zpg_single_title'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> Nothing</option>";
    echo "</select>";
  }
  add_settings_field('zpg_single_caption', 'Image caption:', 'zpg_setting_single_caption', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_caption() {
    $options = get_option('zenphoto_gallery');
    echo "<select id='zpg_single_caption' name='zenphoto_gallery[zpg_single_caption]''>";
      $selected = $options['zpg_single_caption'] === "name" ? " selected" : "";
      echo "<option value='name'{$selected}> Image name</option>";
      $selected = $options['zpg_single_caption'] === "desc" ? " selected" : "";
      echo "<option value='desc'{$selected}> Image description</option>";
      $selected = $options['zpg_single_caption'] === "none" ? " selected" : "";
      echo "<option value='none'{$selected}> Nothing</option>";
    echo "</select>";
  }
  add_settings_field('zpg_single_clip', 'Clip images:', 'zpg_setting_single_clip', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_clip() {
    $options = get_option('zenphoto_gallery');
    $checked = $options['zpg_single_clip'] === "true" ? " checked='yes'" : "";
    echo "<input id='zpg_single_clip' name='zenphoto_gallery[zpg_single_clip]' type='checkbox' value='true'{$checked} /> <small>Check to have thumbnails cropped to the dimensions given below. Otherwise, they will be scaled to the width given below.</small>";
  }
  add_settings_field('zpg_single_clip_w', 'Clip width:', 'zpg_setting_single_clip_w', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_clip_w() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_single_clip_w' name='zenphoto_gallery[zpg_single_clip_w]' size='5' type='text' value='{$options['zpg_single_clip_w']}' />px";
  }
  add_settings_field('zpg_single_clip_h', 'Clip height:', 'zpg_setting_single_clip_h', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_clip_h() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_single_clip_h' name='zenphoto_gallery[zpg_single_clip_h]' size='5' type='text' value='{$options['zpg_single_clip_h']}' />px";
  }
  add_settings_field('zpg_single_width', 'Max image width:', 'zpg_setting_single_width', 'zenphoto_gallery', 'zpg_single');
  function zpg_setting_single_width() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_single_width' name='zenphoto_gallery[zpg_single_width]' size='5' type='text' value='{$options['zpg_single_width']}' />px";
  }

add_settings_section('zpg_style', 'Style Settings', 'zpg_style_text', 'zenphoto_gallery');
  function zpg_style_text() {
    echo 'Configure additional style information for the elements used.';
  }
  add_settings_field('zpg_class_div', 'Class of gallery-surrounding div:', 'zpg_setting_class_div', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_class_div() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_class_div' name='zenphoto_gallery[zpg_class_div]' size='40' type='text' value='{$options['zpg_class_div']}' />";
  }
  add_settings_field('zpg_style_div', 'Style of gallery-surrounding div:', 'zpg_setting_style_div', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_style_div() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_style_div' name='zenphoto_gallery[zpg_style_div]' size='40' type='text' value='{$options['zpg_style_div']}' />";
  }
  add_settings_field('zpg_class_idiv', 'Class of image-surrounding div:', 'zpg_setting_class_idiv', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_class_idiv() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_class_idiv' name='zenphoto_gallery[zpg_class_idiv]' size='40' type='text' value='{$options['zpg_class_idiv']}' />";
  }
  add_settings_field('zpg_style_idiv', 'Style of image-surrounding div:', 'zpg_setting_style_idiv', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_style_idiv() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_style_idiv' name='zenphoto_gallery[zpg_style_idiv]' size='40' type='text' value='{$options['zpg_style_idiv']}' />";
  }
  add_settings_field('zpg_class_link', 'Class of image links:', 'zpg_setting_class_link', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_class_link() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_class_link' name='zenphoto_gallery[zpg_class_link]' size='40' type='text' value='{$options['zpg_class_link']}' />";
  }
  add_settings_field('zpg_style_link', 'Style of image links:', 'zpg_setting_style_link', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_style_link() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_style_link' name='zenphoto_gallery[zpg_style_link]' size='40' type='text' value='{$options['zpg_style_link']}' />";
  }
  add_settings_field('zpg_rel_link', 'Rel attribute of image links:', 'zpg_setting_rel_link', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_rel_link() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_rel_link' name='zenphoto_gallery[zpg_rel_link]' size='40' type='text' value='{$options['zpg_rel_link']}' />";
    echo "<br /><small>Can be used to trigger image link handling by (light|shadow|...)box</small>";
  }
  add_settings_field('zpg_class_image', 'Class of images:', 'zpg_setting_class_image', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_class_image() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_class_image' name='zenphoto_gallery[zpg_class_image]' size='40' type='text' value='{$options['zpg_class_image']}' />";
  }
  add_settings_field('zpg_style_image', 'Style of images:', 'zpg_setting_style_image', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_style_image() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_style_image' name='zenphoto_gallery[zpg_style_image]' size='40' type='text' value='{$options['zpg_style_image']}' />";
  }
  add_settings_field('zpg_class_caption', 'Class of image captions:', 'zpg_setting_class_caption', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_class_caption() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_class_caption' name='zenphoto_gallery[zpg_class_caption]' size='40' type='text' value='{$options['zpg_class_caption']}' />";
  }
  add_settings_field('zpg_style_caption', 'Style of image captions:', 'zpg_setting_style_caption', 'zenphoto_gallery', 'zpg_style');
  function zpg_setting_style_caption() {
    $options = get_option('zenphoto_gallery');
    echo "<input id='zpg_style_caption' name='zenphoto_gallery[zpg_style_caption]' size='40' type='text' value='{$options['zpg_style_caption']}' />";
  }
?>
