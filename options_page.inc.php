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

<div class="wrap">
  <h2>Zenphoto Gallery Options</h2>

  <?php
    if ( !empty($options['zpg_url']) ) {
      if ( !zpg_check_json() ) { ?>
        <div style="border:2px darkred solid;padding:5px;"><p>Remember to copy <span style="font-family:courier,monospace;">json.php</span>
        from the plugin folder to your Zenphoto installation's root folder such that
        it is accessible at <a href="<?php echo $options['zpg_url']; ?>/json.php">
        <?php echo $options['zpg_url']; ?>/json.php</a>.</p>
        <p>If you are sure that this file is where it should be, your
        webserver might forbid <span style="font-family:courier,monospace;">fopen()</span>
        to remote files.</p></div>
<?php }
    }
  ?>

  <div style="float:right; position:fixed; right:10px; width:120px; border:1px solid grey; padding:5px; margin:10px; border-radius:4px; -moz-border-radius:4px;-webkit-radius:4px; background-color: rgb(255, 255, 224);">
    <h3>Resources</h3>
    <ul>
      <li><a href="http://lmazy.verrech.net/zenphoto-gallery/" target="_blank">Homepage</a></li>
      <li><a href="http://lmazy.verrech.net/tag/zenphoto-gallery/" target="_blank">Blog</a></li>
      <li><a href="http://lmazy.verrech.net/zenphoto-gallery/zenphoto-gallery-faq/" target="_blank">FAQ</a></li>
      <li><a href="http://bugs.verrech.net/thebuggenie/zenphotogallery" target="_blank">Bugtracker</a></li>
      <li><a href="http://wordpress.org/tags/zenphoto-gallery?forum_id=10" target="_blank">Feedback</a></li>
      <li><a href="mailto:wordpress@verrech.net" target="_blank">Contact</a></li>
    </ul>
  </div>

  <form action="options.php" method="post">
    <p>
      <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </p>

    <?php settings_fields('zenphoto_gallery'); ?>
    <?php do_settings_sections('zenphoto_gallery'); ?>

    <p>
      <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </p>
  </form>
</div>
