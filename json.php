<?php

/**
 * This file is based on rss.php in Zenphoto 1.3. It accesses the same
 * data but yields (selected) JSON output.
 * It is designed for use with the Wordpress plugin "Zenphoto Gallery".
 *
 * Adaption by Raphael Reitzig (2010)
 * http://senke.verrech.net
 */

require_once(dirname(__FILE__).'/zp-core/folder-definitions.php');
define('OFFSET_PATH', 0);
require_once(ZENFOLDER . "/template-functions.php");
require_once(ZENFOLDER . "/functions-rss.php");
startRSSCache();
if (!getOption('RSS_album_image')) {
	header("HTTP/1.0 404 Not Found");
	header("Status: 404 Not Found");
	include(ZENFOLDER. '/404.php');
	exit();
}
require_once(ZENFOLDER .'/'.PLUGIN_FOLDER . "/image_album_statistics.php");
header('Content-Type: text/x-json');
$rssmode = getRSSAlbumsmode();
$albumfolder = getRSSAlbumnameAndCollection("albumfolder");
$collection = getRSSAlbumnameAndCollection("collection");
$locale = getRSSLocale();
$validlocale = getRSSLocaleXML();
$host = getRSSHost();
$uri = getRSSURI();
$serverprotocol = getOption("server_protocol");
$albumname = getRSSAlbumTitle();
$albumpath = getRSSImageAndAlbumPaths("albumpath");
$modrewritesuffix = getRSSImageAndAlbumPaths("modrewritesuffix");
$imagepath = getRSSImageAndAlbumPaths("imagepath");
$size = getRSSImageSize();
$items = getOption('feed_items'); // # of Items displayed on the feed

if ($rssmode == "albums") {
  $result = array();
} else {
  $result = getImageStatistic($items,getOption("feed_sortorder"),$albumfolder,$collection);
}?>
[
<?php $i=0;
foreach ($result as $item) {
  if($rssmode != "albums") {
    $ext = strtolower(strrchr($item->filename, "."));
    $albumobj = $item->getAlbum();
    $itemlink = $host.WEBPATH.$albumpath.pathurlencode($albumobj->name).$imagepath.pathurlencode($item->filename).$modrewritesuffix;
    $fullimagelink = $host.WEBPATH."/albums/".$albumobj->name."/".$item->filename;
    $imagefile = "albums/".$albumobj->name."/".$item->filename;

    $file = $item->filename;
    $thumburl = $serverprotocol.'://'.$host.$item->getCustomImage($size, NULL, NULL, NULL, NULL, NULL, NULL, TRUE); $title = get_language_string($item->get("title"),$locale);
    $itemcontent = get_language_string($item->get("desc"),$locale);
  }
  else {
    // Insert behaviour for album list?
  }

  if ( $i == 1 ) echo ', ';
?>

  {
    "title" : <?php echo json_encode(get_language_string($item->get("title"),$locale)); ?>,
    "album" : <?php echo json_encode($albumobj->name); ?>,
    "file" : "<?php echo $file; ?>",
    "thumb" : "<?php echo $thumburl; ?>",
    "description" : <?php echo json_encode($itemcontent); ?>
  }<?php $i = 1; } ?>

]

<?php endRSSCache(); ?>
