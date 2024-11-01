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

/**
 * This function returns HTML code that shows the passed list of images
 * given the passed options and shortcode parameters. Note that of all
 * entries in $param only $param['number'] will have any effect on this
 * function; all other parameters are assumed to have overwritten the
 * corresponding values in $options already.
 */
function zpg_create_gallery($param, $options, $images) {
  // Check passed parameters and honor them
  if ( $param['number'] == 0 ) {
    $param['number'] = count($images);
  }

  $result = "";

  // Create HTML attributes for div and links
  $class_div = (!empty($options['zpg_class_div'])) ? $options['zpg_class_div'].' ' : '';

  // Wrapping div (for style possibilities) and table (for alignments)
  $result .= "<div class=\"{$class_div}zpg_gallery_div\"{$class_div}>\n";
  $result .= "<table class=\"zpg_gallery_table\">\n";

  $count = 0;

  // Reorder image list if necessary
  if ( !empty($options['zpg_choice']) && $options['zpg_choice'] === 'earliest' ) {
    $images = array_reverse($images);
  }
  elseif ( !empty($options['zpg_choice']) && $options['zpg_choice'] === 'random' ) {
    shuffle($images);
  }

  // The Loop
  foreach ( $images as $image ) {
    // For each image or for the specified number of images
    if ( $count >= $param['number'] ) {
      break;
    }

    // Break gallery line after the correct number of images
    if ( ($count % $options['zpg_nr_row']) === 0 ) {
      $result .= "<tr>";
    }

    // Open table cell with transparent style and centering
    $result .= "<td>\n";

    // Code for image
    $result .= zpg_create_image($options, $image, true);

    // Close table cell
    $result .= "</td>";

    $count = $count + 1;

    if ( ($count % $options['zpg_nr_row']) === 0 ) {
      $result .= "</tr>";
    }
  }

  // If the last row is not filled completely, close table row
  if ( ($count % $options['zpg_nr_row']) !== 0 ) {
    $result .= "</tr>";
  }

  $result .= "</table></div>";

  return $result;
}

/**
 * This function returns HTML code that shows the passed image given
 * the passed options. If <code>gallery</code> is <code>true</code>,
 * settings for galleries are used, those for single images otherwise.
 * Default is <code>false</false>.
 */
function zpg_create_image($options, $image, $gallery=false) {
  // Register styles
  wp_enqueue_style('zpg_gallery_style_default', plugins_url('', __FILE__).'/style.css.php');

  $result = '';

  /* If the passed image is not part of a gallery, use settings
   * for single images */
  $option_mod = $gallery ? '' : 'single_';

  // Create HTML attributes for div and links
  $class_idiv = (!empty($options['zpg_class_idiv'])) ? $options['zpg_class_idiv'].' ' : '';
  $class_link = (!empty($options['zpg_class_link'])) ? $options['zpg_class_link'].' ' : '';
  $class_image = (!empty($options['zpg_class_image'])) ? $options['zpg_class_image'].' ' : '';
  $class_caption = (!empty($options['zpg_class_caption'])) ? $options['zpg_class_caption'].' ' : '';

  $rel = "";
  if ( !empty($options['zpg_rel_link']) && $options['zpg_link'] === 'image' ) {
    $rel = ' rel="'.$options['zpg_rel_link'].'"';
  }

  // Define link corresponding to the chosen target
  $link = '';
  if ( $options['zpg_'.$option_mod.'link'] === 'page' ) {
    $link = $options['zpg_url'].'/'.$image['album'].'/'.$image['file'].'.php';
  }
  elseif ( $options['zpg_'.$option_mod.'link'] === 'image' ) {
    $link = $options['zpg_url'].'/zp-core/i.php?a='.$image['album'].'&amp;i='.$image['file'];
  }
  elseif ( $options['zpg_'.$option_mod.'link'] === 'album' ) {
    $link = $options['zpg_url'].'/'.$image['album'];
  }
  elseif ( $options['zpg_'.$option_mod.'link'] === 'main' ) {
    $link = $options['zpg_url'];
  }

  // Get dimensions of remote image
  $dim = getimagesize($image['thumb']);
  $width = $dim[0];
  $height = $dim[1];

  // Clip or downscale images corr to settings
  $imgstyle = '';
  if ( $options['zpg_'.$option_mod.'clip'] === 'true' ) {
    // If the image is smaller than the set clip region don't clip
    $tclip = max(array(0, ($height - $options['zpg_'.$option_mod.'clip_h']) / 2));
    $bclip = $height - $tclip;
    $lclip = max(array(0, ($width - $options['zpg_'.$option_mod.'clip_w']) / 2));
    $rclip = $width - $lclip;

    /* Update dimensons for wrapping div needed for correct image
     * positioning */
    $width = $width - 2*$lclip;
    $height = $height - 2*$tclip;

    $imgstyle .= ' position : absolute;';
    $imgstyle .= ' clip:  rect('.$tclip.'px,'.$rclip.'px,'.$bclip.'px,'.$lclip.'px);';
    // Correct positioning wrt wrapping div
    $imgstyle .= ' top : -'.$tclip.'px;';
    $imgstyle .= ' left : -'.$lclip.'px;';
  }
  else {
    $width = $options['zpg_'.$option_mod.'width'];
    $imgstyle .= ' max-width : '.$width.'px;';
  }

  // Use wordpress div for box and caption.
  $result .= "<div class=\"{$class_idiv}zpg_image_div\">";

  if ( $options['zpg_'.$option_mod.'clip'] === 'true' ) {
    // This div is needed to position the clipped image properly
    $result .= "<div style=\"position:relative; width:{$width}px; height:{$height}px;\">\n";
  }

  if ( $options['zpg_'.$option_mod.'link'] !== "none" ) {
    $result .= "<a class=\"{$class_link}zpg_image_link\"{$rel} href=\"{$link}\">\n";
  }

  if ( $options['zpg_'.$option_mod.'title'] === 'name' ) {
    $title = htmlspecialchars($image['title']);
  }
  elseif ( $options['zpg_'.$option_mod.'title'] === 'desc' ) {
    $title = htmlspecialchars($image['description']);
  }

  // Finally, the image!
  $result .= "<img class=\"{$class_image}zpg_image\" src=\"{$image['thumb']}\" alt=\"{$image['title']}\" title=\"{$title}\" style=\"{$imgstyle}\" />\n";

  // Closing all stuff
  if ( $options['zpg_'.$option_mod.'link'] !== "none" ) {
    $result .= "</a>";
  }

  if ( $options['zpg_'.$option_mod.'clip'] === 'true' ) {
    $result.= "</div>\n";
  }

  // Adding caption
  if ( $options['zpg_'.$option_mod.'caption'] !== 'none' ) {
    $result .= "<p class=\"{$class_caption}zpg_caption\">";
    if ( $options['zpg_'.$option_mod.'caption'] === 'name' ) {
      $result .= $image['title'];
    }
    elseif ( $options['zpg_'.$option_mod.'caption'] === 'desc' ) {
      $result .= $image['description'];
    }
    $result .= "</p>";
  }

  $result.= "</div>";

  return $result;
}
?>
