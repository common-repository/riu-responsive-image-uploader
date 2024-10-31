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

namespace riu;

function str_lreplace( $search, $replace, $subject ) {
	$pos = strrpos( $subject, $search );
	if ( $pos !== false ) {
		$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
	}

	return $subject;
}

function getProfiles( $width, $height, $retina ) {
	if ( $retina )
		return array(
			'desktop'       => array( $width / 2, $height / 2, 95 ),
			'mobile'        => array( $width / 2, $height / 2, 85 ),
			'desktopRetina' => array( $width, $height, 85 ),
			'mobileRetina'  => array( $width, $height, 75 )
		);
	else
		return array(
			'desktop' => array( $width, $height, 95 ),
			'mobile'  => array( $width, $height, 85 ),
		);
}

function processAttachment( $attachment_id, $meta = null ) {

	$meta = json_decode( stripslashes( $meta ), true );

	$attachment     = wp_get_attachment_metadata( $attachment_id );

	$profiles = getProfiles( $attachment['width'], $attachment['height'], ( isset( $meta['retina'] ) && $meta['retina'] ) );

	$upload_dir = wp_upload_dir();

	$images = array();

	$meta['width']  = $attachment['width'];
	$meta['height'] = $attachment['height'];

	$meta['ratio'] = $attachment['width'] / $attachment['height'];

	$meta['pathAlias'] = str_lreplace( '.', '%ALIAS%.', $upload_dir['basedir'] . '/' . $attachment['file'] );
	$meta['urlAlias']  = str_lreplace( '.', '%ALIAS%.', $upload_dir['baseurl'] . '/' . $attachment['file'] );

	foreach ( $profiles as $name => $profile ) {

		$path  = $upload_dir['basedir'] . '/' . $attachment['file'];
		$image = new Image( $path );

		$imageProcess = new ImageProcessor( $image );

		$imageProcess->resize( $profile[0], $profile[1] );
		$imageProcess->save( str_lreplace( '.', $name . '.', $path ) );

		$images[ $name ] = array(
			'local' => str_lreplace( '.', $name . '.', $path ),
			'url'   => str_lreplace( '.', $name . '.', $attachment['file'] )
		);

		unset( $image );
		unset( $imageProcess );

	}

	update_post_meta( $attachment_id, 'riuMeta', json_encode( array_merge( $meta, array( 'images' => $images ) ) ) );

}