<div class="wrap mp-wrap">
	<h1><?php echo static::TITLE; ?></h1>
	<hr />
	<div class="mp-section">
		<h3>Shortcodes</h3>
		<table class="widefat striped">
			<thead>
				<tr>
					<th>Shortcode Description</th>
					<th>Shortcode Syntax</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $shortcodes as $shortcode ) : ?>
				<tr>
					<th><?php echo $shortcode::$description; ?></th>
					<td>[<?php echo $shortcode::$name; ?>]

					<?php if ( isset( $shortcode::$args ) ) : ?>
						<h4>Arguments</h4>
						<ul>
						<?php foreach ( $shortcode::$args as $arg ) :
							if ( strpos( $arg, ':req' ) !== false ) :
								$arg = str_replace( ':req', ' <em>required</em>', $arg);
							endif;
							?>
							<li><?php echo $arg; ?></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>