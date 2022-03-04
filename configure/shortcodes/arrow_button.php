<?php
add_shortcode( 'arrow_button', 'bs_arrow_button' );
function bs_arrow_button( $atts ) {
	$args = shortcode_atts( array(
		'text' => 'Learn More',
		'url' => '#',
		'target' => '_self',
		'class' => '',
		'dark_bg' => '0',
	), $atts, 'bs_arrow_button' );
  ob_start();

	$button_text = $args['text'];
	$button_url = $args['url'];
	$button_target = $args['target'];
	$button_class = $args['class'];
	$button_dark_bg = $args['dark_bg'];

	if ( $button_dark_bg === '1' ) {
	?>
		<a class="bs-arrow-btn dark-bg <?php echo $button_class; ?>" href="<?php echo $button_url; ?>" target="<?php echo $button_target; ?>">
			<span><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/src/img/arrow-right-white.svg" alt="a black arrow pointing to the right" /></span>
			<?php echo '<span class="arrow-btn-text">' . $button_text . '</span>'; ?>
		</a>

	<?php } else { ?>

		<a class="bs-arrow-btn <?php echo $button_class; ?>" href="<?php echo $button_url; ?>" target="<?php echo $button_target; ?>">
			<span><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/src/img/arrow-right-black.svg" alt="a black arrow pointing to the right" /></span>
			<?php echo '<span class="arrow-btn-text">' . $button_text . '</span>'; ?>
		</a>

	<?php }

  $bsarrowbutton = ob_get_clean(); return $bsarrowbutton;
}
