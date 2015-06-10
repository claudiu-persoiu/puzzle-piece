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

class Mask
{
    /**
     * Center of mask
     *
     * @var int
     */
    protected $_centerX;
    protected $_centerY;

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
     * Color black for mask creation
     *
     * @var int
     */
    protected $_opaqueColor;

    /**
     * Color with for mask creation
     *
     * @var
     */
    protected $_transparentColor;

    /**
     * Join margin size
     *
     * @var float
     */
    protected $_marginSize;

    /**
     * Base size for resulting image
     *
     * @var float
     */
    protected $_basePieceSize;

    /**
     * Border size of two pixels
     */
    const BORDER_SIZE = 2;

    public function __construct($width,
                                $height,
                                $offsetXTop,
                                $offsetXBottom,
                                $offsetYLeft,
                                $offsetYRight,
                                $typeX,
                                $typeY,
                                $marginSize,
                                $basePieceSize)
    {
        $imageMask = imagecreatetruecolor($width, $height);

        $this->_generateColorsForMask($imageMask);

        $this->_imageWidth = $width;
        $this->_imageHeight = $height;
        $this->_offsetXTop = $offsetXTop;
        $this->_offsetYLeft = $offsetYLeft;
        $this->_offsetXBottom = $offsetXBottom;
        $this->_offsetYRight = $offsetYRight;
        $this->_typeX = $typeX;
        $this->_typeY = $typeY;
        $this->_marginSize = $marginSize;
        $this->_basePieceSize = $basePieceSize;

        // make the central area transparent relative to the mask
        imagefilledrectangle($imageMask,
            ($this->_offsetXTop - 2) * $this->_typeX, // offset X up left
            ($this->_offsetYLeft - 2) * $this->_typeY, // offset Y up left
            ($this->_imageWidth - $this->_offsetXBottom * $this->_typeX), // offset X down right
            ($this->_imageHeight - $this->_offsetYRight * $this->_typeY), // offset Y down right
            $this->_transparentColor);

        $this->_calculateCenter();

        $this->_addMargins($imageMask);

        imagecolortransparent($imageMask, $this->_transparentColor);

        $this->_imageMask = $imageMask;
    }

    protected function _generateColorsForMask($imageMask)
    {
        // opaque color
        $this->_opaqueColor = imagecolorallocate($imageMask, 0, 0, 0);

        // transparent color
        $this->_transparentColor = imagecolorallocate($imageMask, 255, 255, 255);
    }

    protected function _calculateCenter()
    {
        $this->_centerX = ceil($this->_basePieceSize / 2) + $this->_offsetYLeft * $this->_typeY;
        $this->_centerY = ceil($this->_basePieceSize / 2) + $this->_offsetXTop * $this->_typeX;

    }

    protected function _addMargins($imageMask)
    {
        $this->_addMarginsX($imageMask);

        $this->_addMarginsY($imageMask);
    }

    protected function _addMarginsX($imageMask)
    {
        if ($this->_typeX) {
            if ($this->_offsetXTop) { // up
                $this->_addShapeLeftMale($imageMask);
            }

            if ($this->_offsetXBottom) { // down
                $this->_addShapeRightMale($imageMask);
            }
        } else {
            if ($this->_offsetXTop) { // up
                $this->_addShapeLeftFemale($imageMask);
            }

            if ($this->_offsetXBottom) { // down
                $this->_addShapeRightFemale($imageMask);
            }
        }
    }

    protected function _addMarginsY($imageMask)
    {
        if ($this->_typeY) {
            if ($this->_offsetYLeft) { // left
                $this->_addShapeUpMale($imageMask);
            }

            if ($this->_offsetYRight) { // right
                $this->_addShapeDownMale($imageMask);
            }
        } else {
            if ($this->_offsetYLeft) { // left
                $this->_addShapeUpFemale($imageMask);
            }

            if ($this->_offsetYRight) { // right
                $this->_addShapeDownFemale($imageMask);
            }
        }
    }

    protected function _addShapeLeftMale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_marginSize,
            $this->_centerX,
            ($this->_marginSize * 2),
            ($this->_marginSize * 2),
            90,
            270,
            $this->_transparentColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeRightMale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_imageWidth - $this->_marginSize - 1,
            $this->_centerX,
            ($this->_marginSize * 2),
            ($this->_marginSize * 2),
            270,
            90,
            $this->_transparentColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeUpMale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_centerY,
            $this->_marginSize,
            ($this->_marginSize * 2),
            ($this->_marginSize * 2),
            180,
            360,
            $this->_transparentColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeDownMale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_centerY,
            ($this->_imageHeight - $this->_marginSize - 1),
            ($this->_marginSize * 2),
            ($this->_marginSize * 2),
            0,
            180,
            $this->_transparentColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeLeftFemale($imageMask)
    {
        imagefilledarc($imageMask,
            0,
            $this->_centerX,
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            270,
            90,
            $this->_opaqueColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeRightFemale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_imageWidth - 1,
            $this->_centerX,
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            90,
            270,
            $this->_opaqueColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeUpFemale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_centerY,
            0,
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            0,
            180,
            $this->_opaqueColor,
            IMG_ARC_EDGED);
    }

    protected function _addShapeDownFemale($imageMask)
    {
        imagefilledarc($imageMask,
            $this->_centerY,
            $this->_imageHeight - 1,
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            ($this->_marginSize * 2 - self::BORDER_SIZE),
            180,
            360,
            $this->_opaqueColor,
            IMG_ARC_EDGED);
    }

    public function applyToPiece($image)
    {
        // overlap image with mask
        imagecopymerge($image, $this->_imageMask, 0, 0, 0, 0, $this->_imageWidth, $this->_imageHeight, 100);


        // reallocate black for the image to be processed
        $opaqueColor = imagecolorallocate($image, 0, 0, 0);

        imagecolortransparent($image, $opaqueColor);

        return $image;
    }

    public function __destruct()
    {
        if ($this->_imageMask) {
            imagedestroy($this->_imageMask);
        }
    }
}
