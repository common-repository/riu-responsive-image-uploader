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
 * Image class is an adaptation for wordpress integration and store basic parameters as path and mime type of an image
 */

namespace riu;

class Image {
	public $path;
	public $mime;

	function _mime_content_type($filename) {

		if(function_exists("mime_content_type"))
			return mime_content_type($filename);

		try {
			if (extension_loaded('fileinfo')) {
				$result = new finfo();
				if (is_resource($result) === true) {
					return $result->file($filename, FILEINFO_MIME_TYPE);
				}
			}
		} catch (\Exception $e) {
			//
		}

		$extension = strtolower(end(explode('.', $filename)));

		switch ($extension) {
			case 'jpg': return 'image/jpeg';
			case 'jpeg': return 'image/jpeg';
			case 'png': return 'image/png';
			case 'gif': return 'image/gif';
			default:
				return 'image/jpeg';
		}

	}

	function __construct($path) {
		$this->path = $path;
		$this->mime = $this->_mime_content_type($path);
	}
}