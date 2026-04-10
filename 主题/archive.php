<?php
/**
 * 归档页模板
 *
 * 用于分类、标签、作者、日期归档等页面
 * 布局：归档标题 → 描述 → 文章卡片网格 → 分页
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="site-main" class="site-main">
		<!-- ==========================================
		     归档头部信息
		     ========================================== -->
		<section class="archive-header" style="animation: fade-in-up 0.6s ease-out both;">
			<?php if ( is_category() ) :
				$cat = get_queried_object();
				?>
				<h1 class="archive-title">
					<?php echo esc_html__( '分类', 'neo-brutalism-blog' ); ?>: <span class="accent"><?php echo esc_html( single_cat_title( '', false ) ); ?></span>
				</h1>
				<?php if ( ! empty( $cat->description ) ) : ?>
					<p class="archive-description"><?php echo wp_kses_post( $cat->description ); ?></p>
				<?php endif;

			elseif ( is_tag() ) :
				$tag = get_queried_object();
				?>
				<h1 class="archive-title">
					<?php echo esc_html__( '标签', 'neo-brutalism-blog' ); ?>: <span class="accent"><?php echo esc_html( single_tag_title( '', false ) ); ?></span>
				</h1>
				<?php if ( ! empty( $tag->description ) ) : ?>
					<p class="archive-description"><?php echo wp_kses_post( $tag->description ); ?></p>
				<?php endif;

			elseif ( is_author() ) :
				$author_data = get_queried_object();
				?>
				<h1 class="archive-title">
					<?php echo esc_html__( '作者', 'neo-brutalism-blog' ); ?>: <span class="accent"><?php echo esc_html( $author_data->display_name ); ?></span>
				</h1>
				<?php if ( get_the_author_meta( 'description', $author_data->ID ) ) : ?>
					<p class="archive-description"><?php echo wp_kses_post( get_the_author_meta( 'description', $author_data->ID ) ); ?></p>
				<?php endif;

			elseif ( is_date() ) :
				if ( is_day() ) :
					printf(
						'<h1 class="archive-title">%s: <span class="accent">%s</span></h1>',
						esc_html__( '日存档', 'neo-brutalism-blog' ),
						get_the_date()
					);
				elseif ( is_month() ) :
					printf(
						'<h1 class="archive-title">%s: <span class="accent">%s</span></h1>',
						esc_html__( '月存档', 'neo-brutalism-blog' ),
						get_the_date( 'F Y' )
					);
				else :
					printf(
						'<h1 class="archive-title">%s: <span class="accent">%s</span></h1>',
						esc_html__( '年存档', 'neo-brutalism-blog' ),
						get_the_date( 'Y' )
					);
				endif;
			else :
				?>
				<h1 class="archive-title"><span class="accent"><?php the_archive_title(); ?></span></h1>
				<?php the_archive_description( '<p class="archive-description">', '</p>' );
			endif; ?>
		</section>

		<!-- ==========================================
		     内容区域：文章列表 + 侧边栏
		     ========================================== -->
		<div class="content-grid">
			<div class="content-area">
				<!-- 列表标题 -->
				<div class="posts-section-header">
					<h2 class="posts-section-title"><?php esc_html_e( '文章列表', 'neo-brutalism-blog' ); ?></h2>
					<span class="posts-count">
						<?php global $wp_query; /* translators: %s: 文章数量 */ printf( esc_html__( '共 %s 篇', 'neo-brutalism-blog' ), number_format_i18n( $wp_query->found_posts ) ); ?>
					</span>
				</div>

				<!-- 文章卡片网格 -->
				<div class="posts-grid">
					<?php
					if ( have_posts() ) :

						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'card' );

						endwhile;

					else :
						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>
				</div>

				<!-- 分页导航 -->
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
					<div class="pagination-wrapper" style="margin-top: 2.5rem;">
						<?php
						the_posts_pagination(
							array(
								'mid_size'  => 2,
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'class'     => 'pagination',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div><!-- .content-area -->

			<!-- 侧边栏 -->
			<aside class="widget-area" role="complementary">
				<?php get_sidebar(); ?>
			</aside>
		</div><!-- .content-grid -->
	</main><!-- #site-main -->

<?php
get_footer();
