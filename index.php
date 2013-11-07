<?php
/**
 * Image Cache using phpThumb and Mod_Rewrite
 *
 * Copyright (c) 2012 Brett O'Donnell <brett@mrphp.com.au>
 * Source Code: https://github.com/cornernote/php-image-cache
 * Home Page: http://mrphp.com.au/blog/image-cache-using-phpthumb-and-modrewrite
 * License: GPLv3
 */

/**
 * create and serve a thumbnail
 */

// define allowed image sizes
$sizes = array(
    '714x341',
    '346x346',
    '280x164',
    '400x400',
    '390x355',
    '295x154',
    '230x164',
    '180x180',
    '345x260',
    '120x74',
    '276x95',
    '332x260',
    '120x74',
    '170x124',
    '685x340',
    '310x350'
);

// ensure there was a thumb in the URL
if (!$_GET['thumb']) {
    error('no thumb');
}


// get the thumbnail from the URL
$thumb = strip_tags(htmlspecialchars($_GET['thumb']));

// get the image and size
$thumb_array = explode('/',$thumb);
$size =$thumb_array[0];
array_shift($thumb_array);
$image = '../'.implode('/',$thumb_array);
list($width,$height) = explode('x',$size);

// ensure the size is valid
if (!in_array($size,$sizes)) {
    error('invalid size');
}

// ensure the image file exists
if (!file_exists($image)) {
    $image = './placeholder.png';
    $thumb = $size.'/placeholder/elmercurionoticiascuencaecuador.png';
}

// generate the thumbnail
require('pt/phpthumb.class.php');
$phpThumb = new phpThumb();
$phpThumb->setSourceFilename($image);
$phpThumb->setParameter('w',$width);
$phpThumb->setParameter('h',$height);
$phpThumb->setParameter('f',substr($thumb,-3,3)); // set the output format
$phpThumb->setParameter('zc',"TL"); // set the output format

//$phpThumb->setParameter('far','C'); // scale outside
//$phpThumb->setParameter('bg','FFFFFF'); // scale outside
if (!$phpThumb->GenerateThumbnail()) {
    error('cannot generate thumbnail');
}

// make the directory to put the image
if (!mkpath(dirname($thumb),true)) {
    error('cannot create directory');
}

// write the file
if (!$phpThumb->RenderToFile($thumb)) {
    error('cannot save thumbnail');
}

// redirect to the thumb
// note: you need the '?new' or IE wont do a redirect
header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/'.$thumb.'?new');

// basic error handling
function error($error) {
    header("HTTP/1.0 404 Not Found");
    echo '<h1>Not Found</h1>';
    echo 'The image you requested could not be found.';
    echo "An error was triggered: $error";
    exit();
}
//recursive dir function
function mkpath($path, $mode){
    is_dir(dirname($path)) || mkpath(dirname($path), $mode);
    return is_dir($path) || @mkdir($path,0777,$mode);
}
