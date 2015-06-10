<?php

require_once '../../Piece.php';

$x = (int)$_GET['x'];
$y = (int)$_GET['y'];

$size = (float)$_GET['piceSize'];
$margin = (int)$_GET['margin'];

$imgPath = (int) $_GET['img'];

$imgPath .= '.jpg';

if (!file_exists($imgPath)) $imgPath = '../img.jpg';

$obj = new Piece($imgPath, $size, $margin);

$obj->output($x, $y);