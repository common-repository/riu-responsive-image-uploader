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
 * Shortcode function that will output html for RIU javascript plugin
 */

namespace riu;

function Shortcode( $attr ) {
	$defaults = array(
		'attachment' => '', //attachment id

		'attributes' => '', //htm tag attributes injection
		'style'      => '', //css attributes injection

		'ratio'      => '', //image ratio
		'retina'     => false, //image is retina

		'data'       => '', //riu data aof/pof
		'alias'      => '', //path with alias placeholder

		'class'      => '', //classes injection
		'tag'        => 'img', //tag type
		'content'    => '', //tag content
		'closetag'   => true,
		'linear'     => ''
	);

	extract( shortcode_atts( $defaults, $attr ) );

	$attr = array_replace_recursive( $defaults, $attr );

	if ( empty( $attr['attachment'] ) ) {
		return null;
	}

	$meta = get_post_meta( $attr['attachment'], 'riuMeta', true );

	if ( empty( $meta ) ) {
		return null;
	}

	$meta = json_decode( stripslashes( $meta ), true );

	$data  = empty( $attr['data'] ) ? implode( ',', $meta['data']['aof'] ) . ',' . implode( ',', $meta['data']['pof'] ) : $attr['data'];
	$alias = empty( $attr['alias'] ) ? $meta['urlAlias'] : $attr['alias'];

	//TODO get from attr first
	$width  = $meta['width'];
	$height = $meta['height'];

	$style      = empty( $attr['style'] ) ? '' : " style='max-width:{$width}px; max-height:{$height}px; {$attr['style']};'";
	$attributes = empty( $attr['attributes'] ) ? '' : " {$attr['attributes']}";

	$src = str_replace( '%ALIAS%', '', $alias );

	$class = ! empty( $attr['class'] ) ? " {$attr['class']}" : '';

	$ratio = empty( $attr['ratio'] ) ? $meta['ratio'] : 1;

	$linear = '';
	if ( ! empty( $attr['linear'] ) && $attr['linear'] = 0 ) {
		$linear = " linear='0'";
	}

	$compose = "width='{$width}px' height='{$height}px'$linear class='riu{$class}'{$attributes} ratio='{$ratio}' alias='{$alias}' data='{$data}'$style";

	if ( $attr['tag'] == 'img' ) {
		return "<img src='$src' {$compose}/>";
	}

	return "<{$attr['tag']} {$compose}>{$attr['content']}" . ( $attr['closetag'] ? "</{$attr['tag']}>" : '' );

}

add_shortcode( 'riu', 'riu\Shortcode' );