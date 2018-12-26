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

		if ( $fileType !== 'view' ) :
			array_pop( $pathArr );
			$path = implode( '/', $pathArr ) . '/';
		endif;

		return $path;
	}
}

if ( ! function_exists( 'mp_str_to_slug' ) ) {
	/**
	 * Generates URL friendly slug.
	 *
	 * @param string $string
	 * @return string
	 */
	function mp_str_to_slug( string $string)
	{
		return strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $string ) ) );
	}
}

if ( ! function_exists( 'mp_menu_slug' ) ) {
	/**
	 * Generates page slug for dash menus.
	 *
	 * @param string $string
	 * @return string Menu slug
	 */
	function mp_menu_slug( string $string )
	{
		return MP_PLUGIN_SLUG . '-' . mp_str_to_slug( $string );
	}
}

if ( ! function_exists( 'mp_menu_title' ) ) {
	/**
	 * Returns page title for dash menus.
	 *
	 * @param string $title
	 * @return string Menu title
	 */
	function mp_menu_title( string $title )
	{
		return MP_PLUGIN_MENU_TITLE . $title;
	}
}

if ( ! function_exists( 'mp_form_post' ) ) {
	function mp_form_post()
	{
		return admin_url( 'admin-post.php?action=mp_post' );
	}
}

if ( ! function_exists( 'mp_form_action_fields' ) ) {
	function mp_form_action_fields()
	{
		// will return nonce and action fields
	}
}

if ( ! function_exists( 'mp_view_asset' ) ) {
	/**
	 * Echos asset path.
	 *
	 * @todo Improve this or maybe remove it?
	 * @param string $assetPath
	 * @return void
	 */
	function mp_view_asset( string $assetPath )
	{
		echo MP_VIEWS_URL . $assetPath;
	}
}