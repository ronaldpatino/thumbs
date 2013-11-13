<?php

$sizes = array(
    '714x341',//Imagen noticia principal portada
    '346x346',//Imagen caricatura pequena
    '400x400',//Imagen caricatura grande, Carusel widget grande
    '295x154',//Noticias de portada en pagina principal
    '345x260',//Noticia principla de seccion
    '120x74', //Imagen pequena noticia de seccion
    '332x260', //Imagen noticia grande de seccion, Noticia grande en seccion con lista de imgs
    '120x74',//Noticia secundaria de seccion, noticia pequena en seccion con lista de imgs
    '305x475',//Portada Impresa
    '390x355',//Imagen Farandula
    '280x164',//Imagen Pequena sociales, Carrusel widget
    '440x290',//Imagen grande sociales
    '180x180',//Multimedia Pequeno
    '230x164',//Multimedia Pequeno
    '170x124',//Pagina listado de noticias de seccion
    '685x340',//Imagen grande al ver detalle de noticia
    '310x350',//Imagen de mas fotos en seccion
    '220x400',//Portada impresa carrusel
    '390x255'
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
list($width,$height,$page) = explode('x',$size);
$size = $width.'x'.$height;
// ensure the size is valid
if (!in_array($size,$sizes)) {
    error('invalid size');
}

// ensure the image file exists
if (!file_exists($image)) {
    $image = './placeholder.png';
    $thumb = $size.'/placeholder/elmercurionoticiascuencaecuador.png';
}

//Get the right resize method per page
$resize_method = '';
switch ($page)
{
    case 'S':
        $resize_method = 'portrait';
        break;
    case 'P':
        $resize_method = 'crop';
        break;
    default:
        $resize_method = 'crop';
        break;
}


// generate the thumbnail
require('resize.php');
$resizeObj = new resize($image);
$resizeObj->resizeImage($width, $height, $resize_method);

// make the directory to put the image
if (!mkpath(dirname($thumb),true)) {
    error('cannot create directory');
}

// write the file
if (!$resizeObj->saveImage('./'.$thumb, 100) ) {
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
