<?php

if ( ! function_exists('mp_path_resolver') ) {
	function mp_path_resolver( string $path, $fileType = 'view' )
	{
		if ( strpos( $path, '.' ) !== false ) :
			$pathArr = explode( '.', $path );
			$path = str_replace( '.', MP_DS, $path );
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
	/**
	 * Return POST URL form submissions.
	 *
	 * @deprecated 1.0.0 might not be required
	 * @return string
	 */
	function mp_form_post()
	{
		return admin_url( 'admin-post.php?action=mp_post' );
	}
}

if ( ! function_exists( 'mp_form_fields' ) ) {
	function mp_form_fields( $callType, $action, $controller )
	{
		$controllerName = is_string( $controller ) ? $controller : get_class( $controller );
		/** @todo Do we need the 'post' calls request? */
		$formMethod = $callType === 'ajax' ? 'listenAJAX' : 'listenPOST';

		if ( method_exists( $controller, $action ) ) :
			$nonce = wp_create_nonce( MP_FORM_NONCE );
			$fields = "<input type='hidden' value='$nonce' name='". MP_FORM_NONCE ."'>
			<input type='hidden' value='$action' name='mpAction'>
			<input type='hidden' value='$formMethod' name='action'>
			<input type='hidden' value='". str_replace( '\\', ':', $controllerName ) ."' name='mpController'>";
			echo $fields;
		endif;
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

if ( ! function_exists( 'mp_filter_form_data' ) ) {
	/**
	 * Unset unwanted indexes/variables from the data array.
	 *
	 * @param array $array
	 * @return array $array
	 */
	function mp_filter_form_data( $array )
	{
		unset(
			$array[ MP_FORM_NONCE ],
			$array['action'],
			$array['mpAction'],
			$array['mpController'],
			$array['_wp_http_referer']
		);
		$array = array_map( function( $array ) {
			if ( $array === false && $array === '' ) {
				$array = null;
			}
			return $array;
		}, $array );
		return $array;
	}
}

if ( ! function_exists( 'mp_set_session' ) ) {
	function mp_set_session( $sessionKey, $data, $isArray = false )
	{
		if ( $isArray) return $_SESSION[ MP_SESSION_KEY ][ $sessionKey ][] = $data;
		else return $_SESSION[ MP_SESSION_KEY ][ $sessionKey ] = $data;
	}
}

if ( ! function_exists( 'mp_get_session' ) ) {
	function mp_get_session( $sessionKey )
	{
		return isset( $_SESSION[ MP_SESSION_KEY ][ $sessionKey ] ) ? $_SESSION[ MP_SESSION_KEY ][ $sessionKey ] : null;
	}
}

if ( ! function_exists( 'mp_remove_session' ) ) {
	/**
	 * Removes session.
	 * @todo fix this. maybe use func_arg to unset array keys
	 * @param mixed $sessionKey
	 * @return void
	 */
	function mp_remove_session( $sessionKey = null )
	{
		if ( $sessionKey === null ) unset( $_SESSION[ MP_SESSION_KEY ] );
		else unset( $_SESSION[ MP_SESSION_KEY ][ $sessionKey ] );
	}
}

if ( ! function_exists( 'mp_error_json' ) ) {
	/**
	 * Sets JSON response for error
	 *
	 * @uses WP response styling.
	 * @param mixed|array $response
	 * @param integer $code
	 * @return string
	 */
	function mp_error_json( $response = null, int $code )
	{
		$response ? $return['data'] = $response : '';
		$return['success'] = false;

		return json_encode( $return );
	}
}

if ( ! function_exists( 'mp_success_json' ) ) {
	/**
	 * Sets JSON response for success.
	 *
	 * @uses WP response styling.
	 * @param mixed|array $response
	 * @param integer $code
	 * @return string
	 */
	function mp_success_json( $response = null, int $code )
	{
		$response ? $return['data'] = $response : '';
		$return['success'] = true;

		return json_encode( $return );
	}
}