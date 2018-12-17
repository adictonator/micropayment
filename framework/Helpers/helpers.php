<?php

if ( ! function_exists('mp_path_resolver') ) {
	function mp_path_resolver( string $path, $fileType = 'view' )
	{
		if ( strpos( $path, '.' ) !== false ) :
			$pathArr = explode( '.', $path );
			$path = str_replace( '.', DS, $path );
		else:
			$pathArr = explode( '/', $path );
		endif;

		$ext = MP_FILE_TYPES[ $fileType ];
		$fileName = end( $pathArr );
		return $fullPath = MP_VIEWS_DIR . $path . $ext;
	}
}