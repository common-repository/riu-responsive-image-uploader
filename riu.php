<?php
/**
 * Plugin Name: Responsive Image Uploader
 * Plugin URI: http://brandup.ro
 * Description: Responsive Image Uploader
 * Version: 1.0
 * Author: BrandUp
 */

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
include 'src/Javascripts.php';

/*
 * Admin section
 */

include 'src/Attachment.php';

include 'src/Image.php';
include 'src/ImageProcessor.php';

include 'src/Shortcode.php';

include 'src/admin/MediaEdit.php';