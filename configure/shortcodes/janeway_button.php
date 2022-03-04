<?php
add_shortcode( 'janeway_button', 'bs_janeway_button' );
function bs_janeway_button( $atts ) {
	$args = shortcode_atts( array(
		'text' => 'Your button text',
		'icon' => '',
		'background' => '',
		'color' => '',
		'url' => '#',
		'target' => '_self',
		'class' => '',
		'arrows' => '0',
		'align' => 'left',
	), $atts, 'bs_janeway_button' );
  ob_start();

	$button_text = $args['text'];
	$button_icon = $args['icon'];
	$button_color = $args['color'];
	$button_bg = $args['background'];
	$button_url = $args['url'];
	$button_target = $args['target'];
	$button_class = $args['class'];
	$button_arrows = $args['arrows'];
	$button_align = $args['align'];
	?>

	<p class="button-aligner" style="text-align: <?php echo $button_align; ?>;"><a class="bs-btn <?php echo $button_class; if( $button_icon ) echo ' has-icon'; ?>" href="<?php echo $button_url; ?>" <?php if( $button_color || $button_bg ) { ?>style="background-color: <?php echo $button_bg; ?>; color: <?php echo $button_color; ?>;"<?php } ?> target="<?php echo $button_target; ?>">
		<?php if( $button_icon ) { echo '<span class="' . $button_icon .'"></span> '; } echo $button_text; if( $button_arrows === '1' ) { echo ' &raquo;'; } ?>
	</a></p>

  <?php $bsjaneywaybutton = ob_get_clean(); return $bsjaneywaybutton;
}

// Janeway Buttons Wrapper
add_shortcode( 'janeway_button_wrapper', 'bs_janeway_button_wrapper' );
function bs_janeway_button_wrapper( $atts, $content = null ) {
	$args = shortcode_atts( array(
		'align' => 'left',
	), $atts, 'bs_janeway_button_wrapper' );
  ob_start();

	$content = do_shortcode( $content );
	$align = $args['align'];

	echo '<div class="bs-btns-wrapper align' . $align . '">' . $content . '</div>';

	$bsjaneywaybuttonwrapper = ob_get_clean(); return $bsjaneywaybuttonwrapper;
}
