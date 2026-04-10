<?php
/**
 * 主题主模板（首页 / 博客列表页）
 *
 * 显示：Hero 标题区域 → 精选文章双栏卡片 → 最新文章网格 → 加载更多按钮
 * 布局：主内容区 + 侧边栏
 *
 * @package Neo_Brutalism_Blog
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="site-main" class="site-main">
		<?php if ( is_front_page() && ! is_home() ) : ?>
			<!-- 静态首页内容 -->
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

		<?php else : ?>
			<!-- ==========================================
			     Hero 标题区域
			     ========================================== -->
			<section class="page-hero">
				<h1 class="page-hero-title">
					<?php echo esc_html__( '技术', 'neo-brutalism-blog' ); ?><span class="accent"><?php echo esc_html__( '笔记', 'neo-brutalism-blog' ); ?></span>
				</h1>
				<p class="page-hero-desc">
					<?php echo esc_html__( '探索前端开发的无限可能，从设计系统到性能优化，从 CSS 艺术到架构思考。', 'neo-brutalism-blog' ); ?>
				</p>
			</section>

			<!-- ==========================================
			     精选文章区域（双栏大卡片）
			     ========================================== -->
			<div class="featured-post-wrapper" style="animation: fade-in-up 0.6s 0.2s ease-out both;">
				<?php
				$featured_query = neo_brutalism_get_featured_posts( 1 );
				if ( $featured_query->have_posts() ) :
					while ( $featured_query->have_posts() ) : $featured_query->the_post();
						get_template_part( 'template-parts/content', 'featured' );
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

			<!-- ==========================================
			     内容区域：文章列表 + 侧边栏
			     ========================================== -->
			<div class="content-grid">
				<!-- 文章列表主区域 -->
				<div class="content-area">

					<!-- 列表标题栏 -->
					<div class="posts-section-header">
						<h2 class="posts-section-title"><?php esc_html_e( '最新文章', 'neo-brutalism-blog' ); ?></h2>
						<span class="posts-count">
							<?php
							/* translators: %s: 文章总数 */
							echo sprintf(
								esc_html__( '共 %s 篇', 'neo-brutalism-blog' ),
								esc_html( number_format_i18n( $regular_query->found_posts ) )
							);
							?>
						</span>
					</div>

					<!-- 文章卡片网格 -->
					<div class="posts-grid">
						<?php
						// 收集精选文章 ID
						$featured_ids = array();
						$fq           = neo_brutalism_get_featured_posts( -1, true );
						if ( $fq->have_posts() ) {
							while ( $fq->have_posts() ) {
								$fq->the_post();
								$featured_ids[] = get_the_ID();
							}
							wp_reset_postdata();
						}

						// 创建排除精选的次级查询（避免干扰主查询）
						$regular_args = array(
							'post_type'      => 'post',
							'post_status'    => 'publish',
							'posts_per_page' => get_option( 'posts_per_page', 10 ),
							'post__not_in'   => $featured_ids,
							'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
						);
						$regular_query = new WP_Query( $regular_args );

						if ( $regular_query->have_posts() ) :

							while ( $regular_query->have_posts() ) :
								$regular_query->the_post();
								get_template_part( 'template-parts/content', 'card' );
							endwhile;

							// 分页导航
							echo '<div class="pagination">';
							echo paginate_links( array(
								'total'   => $regular_query->max_num_pages,
								'current' => max( 1, get_query_var( 'paged' ) ),
								'mid_size' => 2,
								'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
								'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
							) );
							echo '</div>';

							wp_reset_postdata();
						else :
							get_template_part( 'template-parts/content', 'none' );
						endif;
						?>
					</div><!-- .posts-grid -->

					<!-- 加载更多 / 分页 -->
					<?php if ( have_posts() || $wp_query->max_num_pages > 1 ) : ?>
						<div class="load-more-container">
							<?php
							the_posts_pagination(
								array(
									'mid_size'  => 2,
									'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>',
									'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
									'class'     => 'pagination',
								)
							);
							?>

							<!-- 如果使用 AJAX 加载更多，可取消下面的注释 -->
							<!--
							<button type="button" class="load-more-btn" id="load-more-posts">
								<?php esc_html_e( '加载更多文章', 'neo-brutalism-blog' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
							</button>
							-->
						</div>
					<?php endif; ?>
				</div><!-- .content-area -->

				<!-- 侧边栏 -->
				<aside class="widget-area" role="complementary">
					<?php get_sidebar(); ?>
				</aside>
			</div><!-- .content-grid -->
		<?php endif; ?>
	</main><!-- #site-main -->

<?php get_footer();
