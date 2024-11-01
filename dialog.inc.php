<?php
/*  Copyright 2010 Raphael Reitzig (wordpress@verrech.net)

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

<script type="text/javascript">
  jQuery(document).ready(function($) {
    window.zpg_plugin_url = '<?php echo plugins_url('', __FILE__).'/'; ?>';
    zpg_init($);
  });
</script>

<h3 class="media-title">Add images from Zenphoto</h3>
<form id="form">
  <p id="question">
    What do you want to include?
    <select id="type" name="type">
      <option value="zenimage">Single Image</option>
      <option value="zenlatest">Latest Images</option>
      <option value="zenalbum">Album Gallery</option>
      <option value="zengallery">Custom Gallery</option>
    </select>
  </p>

  <p class="comment">Leave text fields empty in order to use default settings.</p>

  <table id="options">
    <tr>
      <td width="50%">
        <label id="albuml" for="album">Album:</label> <span><input id="album" name="album" type="text" /></span>
        <label id="imagel" for="image">Image:</label> <span><input id="image" name="image" type="text" /></span>
        <label id="namel" for="name">Name:</label> <span><input id="name" name="name" type="text" /></span>
        <label id="descl" for="desc">Description:</label> <span><input id="desc" name="desc" type="text" /></span>
        <label id="imagesl" for="images" title="One image per line. Separate album, file, name and description by commas. Leave out album if you have specified one above.">Images:</label> <span><textarea id="images" name="images" rows="10" cols="30"></textarea></span>
        <label id="shownl" for="shown">Show:</label> <span><select id="shown" name="shown">
              <option value="default">Default</option>
              <option value="earliest">Earliest</option>
              <option value="latest">Latest</option>
              <option value="random">Random</option>
            </select></span>
        <label id="numberl" for="number">Number:</label> <span><input id="number" name="number" type="text" size="4" /></span>
        <label id="rowl" for="row">Images per row:</label> <span><input id="row" name="row" type="text" size="4" /></span>
      </td>
      <td width="50%">
        <label for="link">Link to:</label> <span><select id="link" name="link">
              <option value="default">Default</option>
              <option value="album">Album</option>
              <option value="page">Image Page</option>
              <option value="image">Image</option>
              <option value="none">None</option>
            </select></span>
        <label for="title">Title:</label> <span><select id="title" name="title">
              <option value="default">Default</option>
              <option value="name">Image Name</option>
              <option value="desc">Image Description</option>
              <option value="none">None</option>
            </select></span>
        <label for="caption">Caption:</label> <span><select id="caption" name="caption">
              <option value="default">Default</option>
              <option value="name">Image Name</option>
              <option value="desc">Image Description</option>
              <option value="none">None</option>
            </select></span>
        <label for="width">Max width:</label> <span><input id="width" name="width" type="text" size="4" />px</span>
        <label for="clipw">Clipping:</label> <span><input id="clipw" name="clipw" type="text" size="4" />px x <input id="cliph" name="cliph" type="text" size="4" />px</span>
      </td>
    </tr>
  </table>

  <div id="buttons">
    <input id="preview" type="button" value="Preview" />
    <input id="submit" type="submit" value="Insert" />
  </div>
</form>

<div id="shortcode" class="previewer">
  Nothing here yet; filled by JavaScript later on.
</div>

<div id="rendered" class="previewer">
  Nothing here yet; filled by JavaScript later on.
</div>
