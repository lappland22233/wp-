<?php
/**
 * 自定义页面模板
 *
 * 用于 WordPress 的静态页面（如"关于"、"联系"等）
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="site-main" class="site-main">
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<!-- 页面标题 -->
				<header class="page-hero" style="margin-bottom: 2rem;">
					<h1 class="page-hero-title" style="animation: fade-in-up 0.6s ease-out both;">
						<?php the_title(); ?>
					</h1>
				</header>

				<!-- 页面缩略图 -->
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-post-thumbnail" style="animation: fade-in-up 0.6s 0.1s ease-out both;">
						<?php the_post_thumbnail( 'featured-large' ); ?>
					</div>
				<?php endif; ?>

				<!-- 页面内容 -->
				<div class="single-post-content" style="animation: fade-in-up 0.6s 0.15s ease-out both;">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="page-links"><span class="page-links-label">' . __( '页面:', 'neo-brutalism-blog' ) . '</span>',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<!-- 编辑链接 -->
				<footer class="entry-footer" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--color-border);">
					<?php
					edit_post_link(
						sprintf(
							wp_kses(
								/* translators: %s: 页面标题 */
								__( '编辑 %s', 'neo-brutalism-blog' ),
								array( 'strong' => array() )
							),
							'<strong>' . get_the_title() . '</strong>'
						),
						'<span class="edit-link">',
						'</span>'
					);
					?>
				</footer>
			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// 如果允许评论且开启了评论功能
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; // End of the loop. ?>
	</main><!-- #site-main -->

<?php
get_footer();
