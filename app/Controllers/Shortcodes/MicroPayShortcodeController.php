<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

/**
 * Methods for [micropay] shortcode.
 *
 * @author Adictonator <adityabhaskarsharma@gmail.com>
 * @package MicroPayment
 * @since 1.0.0
 */
class MicroPayShortcodeController extends BaseShortcodeController
{
	/**
	 * Paywall status.
	 *
	 * @var boolean
	 */
	private $wall = true;

	/**
	 * Holds the shortcode content as cache.
	 *
	 * @var string
	 */
	protected $shortcodeContents;

	/**
	 * The tag for the shortcode.
	 *
	 * @var string
	 */
	public static $name = 'micropay';

	/**
	 * Holds the description for the shortcode.
	 *
	 * @var string
	 */
	public static $description = 'Restricts website content';

	/**
	 * Allowed Arguments for the shortcode.
	 *
	 * @var array
	 */
	public static $args = [
		'price:req',
	];

	/**
	 * Assets for the shortcode.
	 *
	 * @var array
	 */
	public $assets = [
		'css' => [
			'sc' => 'app.css',
		],
		'js' => [
			'sc' => 'app.js',
		],
	];

	/**
	 * Sets the unique ID for the locked content and checks for required
	 * attributes.
	 *
	 * @param array $attrs
	 * @param string $content
	 * @return void
	 */
	public function function( $attrs, $content = '' )
	{
		$attrs = shortcode_atts( [
			'price' => isset( $attrs['price'] ) ? $attrs['price'] : $this->getGlobalPrice(),
			'uid' => $this->uniqueShortcodeID( $content ),
		], $attrs, self::$name );

		$this->removeAssetsFromBackend();

		return $this->validateAttributes( $content, $attrs );
	}

	/**
	 * Process the unlocking of the locked content.
	 *
	 * @return void
	 */
	public function unlock()
	{
		mp_set_session( 'toUnlock', $_POST['sid'] );

		$this->api->validate();
	}

	/**
	 * Unlocks specified content(s) by their unique ID.
	 *
	 * @param string|null $shortcodeIDs
	 * @return void
	 */
	public function unlockContent( $shortcodeIDs = null )
	{
		$shortcodeIDs = $shortcodeIDs ? $shortcodeIDs : $_POST['shortcodeIDs'];

		if ( isset( $shortcodeIDs ) ) :
			$shortcodeIDs = explode( ',', $shortcodeIDs );

			foreach ( $shortcodeIDs as $shortcodeID ) :
				$unlocked = mp_get_session( $shortcodeID );

				if ( $unlocked ) {
					$unlocked->status = 'unlocked';
					mp_set_session( $shortcodeID, $unlocked );
				}
			endforeach;

			$return['type'] = 'unlocking-done';
			$return['shortcodeContent'][ $shortcodeID ] = $unlocked->content;

			$this->setResponse( $return );
		else:
			$this->httpCode = 400;
			$this->setResponse( 'Could not unlock content!' );
		endif;

		echo $this->response(1);
	}

	/**
	 * Returns shortcode contents to be unlocked.
	 * Checks if the shortcode is locked.
	 *
	 * @param array $spends
	 * @return array
	 */
	public static function processUnlockResponse( array $spends )
	{
		$return['shortcodeContent'] = [];
		$return['spends'] = [];

		foreach ( $spends as $spend ) :
			if ( mp_get_session( $spend['description'] )->status === 'locked' ) :
				$return['shortcodeContent'][ $spend['description'] ] = mp_get_session( $spend['description'] )->content;
				$return['spends'][] = $spend['description'];
			endif;
		endforeach;

		return $return;
	}

	/**
	 * Checks if paywall is required.
	 *
	 * @return boolean
	 */
	public function hasWall()
	{
		if ( $this->shortcodeContents ) $this->wall = $this->api->needWall( $this->shortcodeContents->attrs->uid );

		return $this->wall;
	}

	/**
	 * Removes `Shortcode API` from WP backend to prevent conflicts.
	 *
	 * @return void
	 */
	public function dequeueScriptsFromAdmin()
	{
		wp_dequeue_script( 'mp-sc' );
	}

	/**
	 * Caches the shortcode content.
	 * Checks if the content is already unlocked.
	 *
	 * @param string $content
	 * @param array $attrs
	 * @return string|mixed Paywall or shortcode content
	 */
	protected function processShortcodeContent( $content, $attrs )
	{
		$checkUnlocked = mp_get_session( $attrs['uid'] );

		mp_set_session( $attrs['uid'], $this->shortcodeContents = (object) [
			'content' => $content,
			'attrs'   => (object) $attrs,
			'status'  => $checkUnlocked ? $checkUnlocked->status : 'locked',
		]);

		return $this->checkWallStatus();
	}

	private function getGlobalPrice()
	{
		$globalPrice = get_post_meta( get_the_ID(), '__mp_paywall_price', true );

		return ! empty( $globalPrice ) ? $globalPrice : null;
	}

	/**
	 * Removes enqueued assets from WP backend to prevent
	 * conflicts.
	 * Check if the admin area specific function `get_current_screen`
	 * exits.
	 *
	 * @return void
	 */
	private function removeAssetsFromBackend()
	{
		if ( function_exists( 'get_current_screen' ) && get_current_screen()->id == 'post' ) :
			add_action( 'wp_print_scripts', [$this, 'dequeueScriptsFromAdmin'], 100 );
			// add_action( 'wp_print_styles', [$this, 'dequeueStylesFromAdmin'], 100 );
		endif;
	}

	/**
	 * Generates and saves MD5 hashed string per locked content.
	 *
	 * @param string $content
	 * @return string MD5 hashed string
	 */
	private function uniqueShortcodeID( $content )
	{
		$uID = md5( $content );
		update_post_meta( get_the_ID(), MP_SHORTCODE_UID . $uID , $uID );

		return $uID;
	}

	/**
	 * Displays the paywall content.
	 *
	 * @return string|mixed Paywall HTML
	 */
	private function wall()
	{
		$this->viewMessage = self::VIEW_WALL_MESSAGE;
		return $this->getWallContent();
	}

	/**
	 * Fetches the paywall HTML content.
	 *
	 * @return string|mixed Paywall HTML
	 */
	private function getWallContent()
	{
		ob_start();
		$this->setView( 'shortcode.wall' );
		$wallContent = ob_get_contents();
		ob_end_clean();

		return $wallContent;
	}

	/**
	 * Checks if paywall is required for the current session.
	 *
	 * @return string|mixed Paywall or shortcode content
	 */
	private function checkWallStatus()
	{
		if ( $this->hasWall() ) return $this->wall();
		else return $this->getShortcodeContent();
	}

	/**
	 * Returns the shortcode content to the DOM.
	 *
	 * @return string
	 */
	private function getShortcodeContent()
	{
		return $this->shortcodeContents->content;
	}
}