<?php
/**
 * The template part for displaying single posts
 *
 * @package    WordPress
 * @subpackage WPSaaS
 * @since      WP SaaS 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' );

		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			wpsaas_entry_date();
		}
		?>
	</header><!-- .entry-header -->

	<?php wpsaas_excerpt(); ?>

	<?php wpsaas_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

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
		<?php
		wpsaas_entry_meta();

		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'wpsaas' ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);

		if ( '' !== get_the_author_meta( 'description' ) ) {
			get_template_part( 'template-parts/biography' );
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
