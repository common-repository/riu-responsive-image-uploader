<?php
/*
    This file is part of RIU - Responsive Image Uploader.

    RIU is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    RIU is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * This class offers rapid gd image operations for resize, crop and compression quality
 * Is used to generate the 4 image types for different devices
 */

namespace riu;

class ImageProcessor {

	/**
	 * @var Image
	 */
	protected $image;

	/**
	 * @var Resource
	 */
	protected $resource;

	/**
	 * @var int
	 */
	public $width;

	/**
	 * @var int
	 */
	public $height;

	/**
	 * @var int
	 */
	protected $quality;

	/**
	 * @var string
	 */
	protected $path;

	public function __construct($image)
	{
		$this->path = $image->path;

		if (file_exists($this->path)) {

			$this->image = $image;

			list($this->width, $this->height) = getimagesize($this->path);

			switch ($image->mime) {
				case 'image/jpeg':
					$this->resource = imagecreatefromjpeg($this->path);
					$this->quality = 100;
					break;

				case 'image/png':
					$this->resource = imagecreatefrompng($this->path);
					imagealphablending($this->resource, false);
					imagesavealpha($this->resource, true);
					$this->quality = 0;
					break;

				case 'image/gif':
					$this->resource = imagecreatefromgif($this->path);
					break;

				default:
					throw(new \Exception('Wrong mime-type.'));
			}

		} else
			throw(new \Exception('File ' . $image->getFullPath() . ' does not exist.'));

	}

	public function resize($width, $height)
	{

		$newImage = imagecreatetruecolor($width, $height);
		imagecopyresized($newImage, $this->resource, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
		imagedestroy($this->resource);

		$this->resource = $newImage;
		$this->width = $width;
		$this->height = $height;

	}

	public function crop($x, $y, $width, $height)
	{

		$newImage = imagecreatetruecolor($width, $height);
		imagecopyresized($newImage, $this->resource, 0, 0, $x, $y, $width, $height, $width, $height);
		imagedestroy($this->resource);

		$this->resource = $newImage;
		$this->width = $width;
		$this->height = $height;

	}

	public function save($path,$quality=100)
	{
		if ($this->image->mime == "image/jpeg")
			ImageJPEG($this->resource, $path, $quality);

		else if ($this->image->mime == "image/gif")
			ImageGIF($this->resource, $path);

		else if ($this->image->mime == "image/png")
			ImagePNG($this->resource, $path, 10-$quality/10);

	}

}