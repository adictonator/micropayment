<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Exceptions\ViewErrorException;

trait ViewsTrait
{
	/**
	 * Assets array for the current class.
	 *
	 * @var array
	 */
	protected $assets = [];

	public function setView( string $path, $data = null )
	{
		if ( null !== $data ) extract( $data );

		$filePath = $this->resolveViewPath( $path );

		if ( null !== $filePath ) :
			include $filePath;
		endif;
	}

	public function resolveViewPath( string $path )
	{
		$filePath = mp_path_resolver( $path, 'view' );

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

	public function setAssets( $assets )
	{
		$this->assets = $assets;
	}
}