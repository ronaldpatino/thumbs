<?php
$PHPTHUMB_CONFIG['cache_directory'] = dirname(__FILE__).'/../../../autothumb/cache/';         // set the cache directory relative to the phpThumb() installation
//$PHPTHUMB_CONFIG['cache_directory'] = $PHPTHUMB_CONFIG['document_root'].'/phpthumb/cache/'; // set the cache directory to an absolute directory for all source images
//$PHPTHUMB_CONFIG['cache_directory'] = './cache/';                                      


echo $PHPTHUMB_CONFIG['cache_directory'] ;
echo " ++++++++++++";
?>
