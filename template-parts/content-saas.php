<?php
/**
 * The template part for displaying content
 *
 * @package    WordPress
 * @subpackage WPSaaS
 * @since      WP SaaS 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'wpsaas' ); ?></span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title">App: <a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->

	<?php //wpsaas_excerpt(); ?>

	<?php wpsaas_post_thumbnail();


	$types    = get_the_terms( get_the_ID(), 'saas-type' );
	$features = get_the_terms( get_the_ID(), 'saas-feature' );

	if ( sizeof( $types ) ) {

		$type_results = array();
		foreach ( $types as $key => $type ) {
			$type_results[] =  '<a href="' . esc_url( get_term_link( $type->term_id ) ) . '">' . $type->name . '</a>';
		} // end foreach
		unset( $type );

		echo '<p><span class="bold">WPSaaS Type:</span> ';
		echo implode(', ', $type_results);
		echo '</p>';
	}

	if ( sizeof( $features ) ) {

		$feature_results = array();
		foreach ( $features as $key => $feature ) {

			$feature_results[] = '<a href="' . esc_url( get_term_link( $feature->term_id ) ) . '">' . $feature->name . '</a>';

		} // end foreach
		unset( $feature );

		echo '<p><span class="bold">WPSaaS Featues:</span> ';
		echo implode(', ', $feature_results);
		echo '</p>';
	}


	?>

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_excerpt();

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wpsaas' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wpsaas' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php wpsaas_entry_meta(); ?>
		<?php
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'wpsaas' ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
