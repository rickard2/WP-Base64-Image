<?php
/*
Plugin Name: Base 64 Image
Plugin URI: http://github.com/rickard2/WP-Base64-Image
Description: Base 64 Image
Author: Rickard Andersson
Version: 0.00000000000001 alpha
Author URI: https://0x539.se/
*/

function b64img_insert_post_data( $post ) {

	$count = preg_match_all('/ src=\\\"(http[^"]+)\\\"/', $post['post_content'], $matches);

	if ( !$count || $count == 0 ) {
		return $post;
	}

	foreach ( $matches[1] as $key => $image ) {

		switch ( strtolower( substr( $image, -3, 3) ) ) {
			case 'jpg': $mime = 'image/jpeg'; break;
			case 'gif': $mime = 'image/gif'; break;
			case 'png': $mime = 'image/png'; break;
			default: $mime = false;
		}

		if ( $mime !== false ) {

			$new_image = ' src="data:' . $mime . ';base64,' . base64_encode( file_get_contents($image) ) . '\" data-image-url=\"' . $image . '\"';

			$post['post_content'] = str_replace( $matches[0][ $key ], $new_image, $post['post_content'] );

			unset($new_image);
		}
	}
		
	return $post;
}

add_filter( 'wp_insert_post_data', 'b64img_insert_post_data', 99999 );
