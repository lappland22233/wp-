<?php
/**
 * 搜索结果页模板
 *
 * 显示搜索框 + 结果统计 + 匹配的文章卡片网格
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="site-main" class="site-main">
		<!-- 搜索框 -->
		<section class="search-section" style="margin-bottom: 2.5rem; animation: fade-in-up 0.6s ease-out both;">
			<div class="sidebar-widget" style="max-width: 600px; margin: 0 auto;">
				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="search-input-2" class="sr-only"><?php esc_attr_e( '搜索文章', 'neo-brutalism-blog' ); ?></label>
					<input
						type="search"
						id="search-input-2"
						class="search-form-input"
						placeholder="<?php esc_attr_e( '输入关键词...', 'neo-brutalism-blog' ); ?>"
						value="<?php echo esc_attr( get_search_query() ); ?>"
						name="s"
						autocomplete="off"
					/>
					<button type="submit" class="search-form-button" aria-label="<?php esc_attr_e( '搜索', 'neo-brutalism-blog' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
					</button>
				</form>
			</div>
		</section>

		<!-- 搜索结果统计 -->
		<div class="search-results-info">
			<?php
			global $wp_query;
			$search_term = get_search_query();

			if ( $wp_query->found_posts > 0 ) {
				/* translators: 1: 搜索词, 2: 结果数 */
				printf(
					esc_html__( '搜索 "%1$s" 共找到 %2$s 篇相关文章', 'neo-brutalism-blog' ),
					'<strong>' . esc_html( $search_term ) . '</strong>',
					number_format_i18n( $wp_query->found_posts )
				);
			} else {
				/* translators: %s: 搜索词 */
				printf(
					esc_html__( '未找到与 "%s" 相关的文章', 'neo-brutalism-blog' ),
					'<strong>' . esc_html( $search_term ) . '</strong>'
				);
			}
			?>
		</div>

		<!-- 内容区域 -->
		<div class="content-grid" style="display: grid; gap: 2.5rem; grid-template-columns: 1fr 320px;">
			<div class="content-area">
				<!-- 搜索结果列表 -->
				<div class="posts-grid">
					<?php
					if ( have_posts() ) :

						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'card' );

						endwhile;

					else :
						// 无结果提示
						?>
						<div class="no-results not-found" style="text-align: center; padding: 3rem 1rem;">
							<p><?php esc_html_e( '请尝试使用其他关键词进行搜索。', 'neo-brutalism-blog' ); ?></p>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-home-btn" style="display: inline-flex; margin-top: 1rem;">
								<?php esc_html_e( '返回首页', 'neo-brutalism-blog' ); ?>
							</a>
						</div>
					<?php
					endif;
					?>
				</div>

				<!-- 分页 -->
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
