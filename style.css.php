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
  require_once('../../../wp-load.php');
  require_once('../../../wp-includes/post.php');

  $options = get_option('zenphoto_gallery');

  function _prettify($messy) {
    $result = "";

    if ( !empty($messy) ) {
      foreach ( preg_split('/;/', trim($messy)) as $prop ) {
        $prop = trim($prop);
        if ( !empty($prop) ) {
          $result .= "\n  ".$prop.";";
        }
      }
    }

    return $result;
  }

  header("Content-type: text/css");
?>

div.zpg_gallery_div {<?php echo _prettify($options['zpg_style_div']); ?>

}

div.zpg_image_div {<?php echo _prettify($options['zpg_style_idiv']); ?>

}

a.zpg_image_link {<?php echo _prettify($options['zpg_style_link']); ?>

}

img.zpg_image {<?php echo _prettify($options['zpg_style_image']); ?>

}

p.zpg_caption {<?php echo _prettify($options['zpg_style_caption']); ?>

}

table.zpg_gallery_table {
  border-style : none             !important;
  margin-left : auto              !important;
  margin-right : auto             !important;
  background-color : transparent  !important;
}

table.zpg_gallery_table tr {
  border-style : none             !important;
  background-color : transparent  !important;
}

table.zpg_gallery_table td {
  text-align : center             !important;
  vertical-align : middle         !important;
  border-style : none             !important;
  background-color : transparent  !important;
}
