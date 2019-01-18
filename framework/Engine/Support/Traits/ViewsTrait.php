<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Exceptions\ViewErrorException;

trait ViewsTrait
{
	private $path;

	public function setView( string $path, $data = null )
	{
		if ( null !== $data ) extract( $data );

		$this->path = $path;
		$filePath = $this->resolveViewPath( $this->path );

		if ( null !== $filePath ) :
			$this->enqueueAssets();
			include $filePath;
		endif;
	}

	public function resolveViewPath( string $path )
	{
		$filePath = mp_path_resolver( $path, 'view' );
		$filePath = MP_VIEWS_DIR . $filePath . MP_VIEWS_EXT;

		try {
			if ( ! file_exists( $filePath ) ) :
				throw new ViewErrorException( $filePath );
			else:
				return $filePath;
			endif;
		} catch ( ViewErrorException $e ) {
			echo $e->msg();
		}
	}

	/**
	 * Undocumented function
	 * @todo Heavy improvements needed.
	 * @return void
	 */
	public function enqueueAssets()
	{
		if ( isset( $this->assets['css'] ) ) :
			foreach ( $this->assets['css'] as $key => $asset ) :
				$filePath = mp_path_resolver( $this->path, 'asset' );
				$assetPath  = MP_VIEWS_URL . $filePath . 'assets/css/' . $asset;

				wp_enqueue_style( 'mp-' . $key, $assetPath, [], MP_VER );
			endforeach;
		endif;

		if ( isset( $this->assets['js'] ) ) :
			foreach ( $this->assets['js'] as $key => $asset ) :
				$filePath = mp_path_resolver( $this->path, 'asset' );
				$assetPath  = MP_VIEWS_URL . $filePath . 'assets/js/' . $asset;

				wp_enqueue_script( 'mp-' . $key, $assetPath, ['jquery'], MP_VER, true );
			endforeach;
		endif;
	}
}