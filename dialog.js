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

function zpg_init($) {
  // Hide all elements that should be hidden in the start
  setVisibilities($, [true,true,true,true,false,false,false,false]);
  $('.previewer').hide();

  // Set listeners
  $('#type').change(function(event) {
    var val = $('#type').val();

    if ( val === 'zenimage' ) {
      setVisibilities($, [true,true,true,true,false,false,false,false]);
    }
    else if ( val === 'zenlatest' ) {
      setVisibilities($, [false,false,false,false,false,true,true,true]);
    }
    else if ( val === 'zenalbum' ) {
      setVisibilities($, [true,false,false,false,false,true,true,true]);
    }
    else if ( val === 'zengallery' ) {
      setVisibilities($, [true,false,false,false,true,true,true,true]);
    }
  });

  $('#preview').click(function() {
    preview($, createShortcode($));
  });

  $('#submit').click(function() {
    var win = window.dialogArguments || opener || parent || top;
    win.send_to_editor(createShortcode($));
  });
}

/**
 * This function creates a shortcode based on the currently
 * entered values.
 */
function createShortcode($) {
  var result = '[' + $('#type').val();

  // Get shortcode parameters
  var numeric = /^\d+$/
  var val = null;
  var params = new Array();

  val = $.trim($('#album').val());
  if ( val !== '' ) {
    params.push('album=' + val);
  }
  val = $.trim($('#image').val());
  if ( val !== '' ) {
    params.push('image=' + val);
  }
  val = $.trim($('#name').val());
  if ( val !== '' ) {
    params.push('name="' + val + '"');
  }
  val = $.trim($('#desc').val());
  if ( val !== '' ) {
    params.push('desc="' + val + '"');
  }

  val = $('#shown').val();
  if ( val !== 'default' ) {
    params.push('shown=' + val);
  }

  val = $.trim($('#number').val());
  if ( val !== '' && numeric.test(val) ) {
    params.push('number=' + val);
  }
  val = $.trim($('#row').val());
  if ( val !== '' && numeric.test(val) ) {
    params.push('row=' + val);
  }

  val = $('#link').val();
  if ( val !== 'default' ) {
    params.push('link=' + val);
  }
  val = $('#title').val();
  if ( val !== 'default' ) {
    params.push('title=' + val);
  }
  val = $('#caption').val();
  if ( val !== 'default' ) {
    params.push('caption=' + val);
  }

  val = $.trim($('#width').val());
  if ( val !== '' && numeric.test(val) ) {
    params.push('width=' + val);
  }
  else {
    var val1 = $.trim($('#clipw').val())
    var val2 = $.trim($('#cliph').val())

    if ( val1 !== '' && numeric.test(val1) ) {
      if ( val2 !== '' && numeric.test(val2) ) {
        params.push('clip=' + val1 + 'x' + val2);
      }
    }
  }

  result += ' ' + params.join(' ') + ']';

  // In case of custom gallery, create content
  if ( $('#type').val() === 'zengallery' ) {
    result += $('#images').val().replace(/[\n\f\r]+/g,';') + '[/zengallery]';
  }

  return result;
}

/**
 * This function previews the passed shortcode wrapped in some
 * pseudo text.
 */
function preview($, shortcode) {
  $('#shortcode').html(shortcode);
  $('#rendered').html('Working...');

  var text = pseudotext + shortcode + pseudotext;
  // parse shortcode via ajax
  $.ajax({
      //url : window.zpg_plugin_url + 'shortcodeparser.ajax.php',
      url : ajaxurl,
      data : { action : 'zpg_preview', text : text },
      type : 'POST',
      error : function(req, stat, err) {
          $('#rendered').html('<div class="errorbox">' + stat + ': ' + err + '</div>');
        },
      success : function(data, stat, req) {
          $('#rendered').html(data);
        }
    });
  $('.previewer').show();
}

var hideable = ['#album', '#albuml', '#image', '#imagel',
                '#name', '#namel', '#desc', '#descl',
                '#images', '#imagesl', '#shown', '#shownl',
                '#number', '#numberl', '#row', '#rowl'];
/**
 * Sets visibility for those elements that are switched
 * often. Parameter v is an array that contains booleans
 * (true for visible) in the following order:
 * [album,image,images,shown,number,row]
 * Note that elements' values are set to default when hidden
 */
function setVisibilities($, v) {
  for ( var i=0; i<hideable.length; i+=1 ) {
    setVisibility($, hideable[i], v[Math.floor(i/2)]);
  }
}

/**
 * Sets visibility of the element with the specified id. After a call
 * with this method, said element will be visible if and only if parameter
 * v is true. The element's value is set to its default.
 */
function setVisibility($, id, v) {
  var isV = !$(id).is(':hidden');

  if ( isV && !v ) {
    $(id).hide();
    $(id).val('');
  }
  else if ( !isV && v ) {
    $(id).show();
  }
}

var pseudotext = '<p>Lorem ipsum delenit legendos consetetur ex vim, ei vel paulo dolor inimicus. Delenit minimum delectus nec no. Legimus nominavi vix ex, nam cu dico alia congue. Te errem iudico nec, dicit feugiat nonummy ius in, vel natum doctus nostrud eu. Nominavi expetenda id his. Kasd mollis placerat eos in, no usu debet sanctus splendide.</p>'
