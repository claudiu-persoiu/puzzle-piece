<?php

/**
 * Copyright (c) 2015 Claudiu Persoiu (http://www.claudiupersoiu.ro/)
 *
 * This file is part of "PuzzlePiece".
 *
 * Official project page: https://github.com/claudiu-persoiu/puzzle-piece
 *
 * "PuzzlePiece" is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 * PuzzlePiece is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

include 'Mask.php';

class Piece
{
    /**
     * Maximum number of elements for the current image
     *
     * @var float
     */
    protected $_imgMaxElementsX;
    protected $_imgMaxElementsY;

    /**
     * Original image size
     *
     * @var float
     */
    protected $_originalImageWidth;
    protected $_originalImageHeight;

    /**
     * Base size for resulting image
     *
     * @var float
     */
    protected $_basePieceSize;

    /**
     * Join margin size
     *
     * @var float
     */
    protected $_marginSize;

    /**
     * Image path on disk
     *
     * @var string
     */
    protected $_imagePath;

    /**
     * Position of the current element inside the puzzle
     *
     * @var int
     */
    protected $_positionX = 0;
    protected $_positionY = 0;

    /**
     * Offset margin for the element sides
     *
     * @var int
     */
    protected $_offsetXTop = 0;
    protected $_offsetXBottom = 0;
    protected $_offsetYLeft = 0;
    protected $_offsetYRight = 0;

    /**
     * Type of the piece male/female
     *
     * @var int
     */
    protected $_typeX = 0;
    protected $_typeY = 0;

    /**
     * Width of resulting image
     *
     * @var float
     */
    protected $_imageWidth;

    /**
     * Height of resulting image
     *
     * @var float
     */
    protected $_imageHeight;

    /**
     * Element position type
     */
    const POSITION_FIRST = 'first';
    const POSITION_LAST = 'last';
    const POSITION_CENTER = 'center';

    /**
     * @param $imagePath
     * @param $basePieceSize
     * @param $marginSize
     */
    public function __construct($imagePath, $basePieceSize, $marginSize)
    {
        $this->_basePieceSize = ceil($basePieceSize);
        $this->_marginSize = ceil($marginSize);
        $this->_imagePath = $imagePath;

        list($this->_originalImageWidth, $this->_originalImageHeight) = getimagesize($imagePath);

        $this->_calculateMaxElementsRelativeToPieceSize();
    }

    /**
     * @param $x
     * @param $y
     * @param null $path
     * @return bool
     */
    public function output($x, $y, $path = null)
    {
        $this->_setElementPositionAndSex($x, $y);

        $this->_calculateDimensionsX();
        $this->_calculateDimensionsY();

        $baseImage = $this->_createBaseImage($this->_imagePath);

        $mask = $this->_createMask();
        $puzzleImage = $mask->applyToPiece($baseImage);

        if (!$path) {
            header('Content-Type: image/gif');
        }

        return imagegif($puzzleImage, $path);
    }

    protected function _calculateDimensionsX()
    {
        $marginType = $this->_getMarginType($this->_positionX, $this->_imgMaxElementsX);

        $this->_offsetXTop = ($marginType == self::POSITION_FIRST) ? 0 : $this->_marginSize;
        $this->_offsetXBottom = ($marginType == self::POSITION_LAST) ? 0 : $this->_marginSize;

        $this->_imageWidth = $this->_getWidth($marginType);
    }

    protected function _calculateDimensionsY()
    {
        $marginType = $this->_getMarginType($this->_positionY, $this->_imgMaxElementsY);

        $this->_offsetYLeft = ($marginType == self::POSITION_FIRST) ? 0 : $this->_marginSize;
        $this->_offsetYRight = ($marginType == self::POSITION_LAST) ? 0 : $this->_marginSize;

        $this->_imageHeight = $this->_getHeight($marginType);
    }

    /**
     * @param $marginType
     * @return float
     */
    protected function _getWidth($marginType)
    {
        return $this->_basePieceSize +
        ($this->_marginSize * $this->_typeX * (($marginType == self::POSITION_CENTER) ? 2 : 1));
    }

    /**
     * @param $marginType
     * @return float
     */
    protected function _getHeight($marginType)
    {
        return $this->_basePieceSize +
        ($this->_marginSize * $this->_typeY * (($marginType == self::POSITION_CENTER) ? 2 : 1));
    }

    /**
     * @param $position
     * @param $maxElements
     * @return string
     */
    protected function _getMarginType($position, $maxElements)
    {
        if ($position == 0) {
            return self::POSITION_FIRST;
        }

        if ($position == $maxElements) {
            return self::POSITION_LAST;
        }

        return self::POSITION_CENTER;
    }

    protected function _calculateMaxElementsRelativeToPieceSize()
    {
        // calculate max overall size relative to piece size
        $maxWidth = floor($this->_originalImageWidth / $this->_basePieceSize) * $this->_basePieceSize;
        $maxHeight = floor($this->_originalImageHeight / $this->_basePieceSize) * $this->_basePieceSize;

        $this->_imgMaxElementsX = round(($maxWidth / $this->_basePieceSize) - 1, 0);
        $this->_imgMaxElementsY = round(($maxHeight / $this->_basePieceSize) - 1, 0);
    }

    /**
     * @param $x
     * @param $y
     */
    protected function _setElementPositionAndSex($x, $y)
    {
        // set element position
        $this->_positionX = ($x <= $this->_imgMaxElementsX) ? $x : 0;
        $this->_positionY = ($y <= $this->_imgMaxElementsY) ? $y : 0;

        // check if element is male or female
        $this->_typeX = 1 - ($this->_positionX % 2);
        $this->_typeY = 1 - ($this->_positionY % 2);
    }

    /**
     * @param $imagePath
     * @return resource
     */
    protected function _createBaseImage($imagePath)
    {
        $originalImg = imagecreatefromjpeg($imagePath);

        // base image
        $baseImage = imagecreatetruecolor($this->_imageWidth, $this->_imageHeight);

        imagecopyresampled($baseImage, // base image
            $originalImg, // image from jpeg
            -($this->_positionX * $this->_basePieceSize - ($this->_offsetXTop * $this->_typeX)), // offset pe X
            -($this->_positionY * $this->_basePieceSize - ($this->_offsetYLeft * $this->_typeY)), // offset pe Y
            0, //
            0,
            $this->_originalImageWidth, // width of image
            $this->_originalImageHeight, // height of image
            $this->_originalImageWidth, // width of image
            $this->_originalImageHeight); // height of image

        // destroy original image
        imagedestroy($originalImg);

        return $baseImage;
    }

    public function getMaxElementsX()
    {
        return $this->_imgMaxElementsX;
    }

    public function getMaxElementsY()
    {
        return $this->_imgMaxElementsY;
    }

    protected function _createMask()
    {
        return new Mask($this->_imageWidth,
            $this->_imageHeight,
            $this->_offsetXTop,
            $this->_offsetXBottom,
            $this->_offsetYLeft,
            $this->_offsetYRight,
            $this->_typeX,
            $this->_typeY,
            $this->_marginSize,
            $this->_basePieceSize);
    }
}
