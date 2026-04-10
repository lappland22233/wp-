<?php
/**
 * 侧边栏模板
 *
 * 包含：搜索框 / 分类列表 / 热门文章 / 标签云 / 邮件订阅
 * 同时支持 WordPress 小工具系统
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
	<!-- 使用 WordPress 小工具 -->
	<?php dynamic_sidebar( 'blog-sidebar' ); ?>

<?php else : ?>
	<!-- 默认侧边栏内容（当未配置任何小工具时显示） -->

	<!-- ==========================================
	     搜索小工具
	     ========================================== -->
	<div class="sidebar-widget">
		<h3 class="sidebar-widget-title">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
			<?php esc_html_e( '搜索文章', 'neo-brutalism-blog' ); ?>
		</h3>

		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="search-input-1" class="sr-only"><?php esc_attr_e( '搜索文章', 'neo-brutalism-blog' ); ?></label>
			<input
				type="search"
				id="search-input-1"
				class="search-form-input"
				placeholder="<?php esc_attr_e( '输入关键词...', 'neo-brutalism-blog' ); ?>"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				name="s"
				autocomplete="off"
			/>
			<button type="submit" class="search-form-button" aria-label="<?php esc_attr_e( '搜索', 'neo-brutalism-blog' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
		</form>
	</div>

	<!-- ==========================================
	     分类列表
	     ========================================== -->
	<div class="sidebar-widget">
		<h3 class="sidebar-widget-title">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
			<?php esc_html_e( '分类', 'neo-brutalism-blog' ); ?>
		</h3>

		<ul class="category-list">
			<?php
				$categories = get_categories(
					array(
						'orderby'    => 'count',
						'order'      => 'DESC',
						'hide_empty' => true,
						'number'     => 10,
					)
				);

			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
				foreach ( $categories as $cat ) :
					?>
					<li class="category-list-item">
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
							<span class="category-list-name"><?php echo esc_html( $cat->name ); ?></span>
							<span class="category-count"><?php echo esc_html( number_format_i18n( $cat->count ) ); ?></span>
						</a>
					</li>
				<?php
				endforeach;
			endif;
			?>
		</ul>
	</div>

	<!-- ==========================================
	     热门文章（按评论数排序）
	     ========================================== -->
	<div class="sidebar-widget">
		<h3 class="sidebar-widget-title">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
			<?php esc_html_e( '热门文章', 'neo-brutalism-blog' ); ?>
		</h3>

		<ul class="trending-list">
			<?php
			$trending = neo_brutalism_get_trending_posts( 5 );
			if ( $trending->have_posts() ) :
				$rank = 0;
				while ( $trending->have_posts() ) : $trending->the_post();
					$rank++;
				?>
				<li class="trending-list-item">
					<a href="<?php the_permalink(); ?>">
						<span class="trending-rank"><?php echo esc_html( $rank ); ?></span>
						<span class="trending-title"><?php echo esc_html( wp_trim_words( get_the_title(), 12, '...' ) ); ?></span>
					</a>
				</li>
				<?php
				endwhile;
				wp_reset_postdata();
			else :
				echo '<li><p style="font-size: 0.875rem; color: var(--color-muted-foreground); padding: 0.5rem;">' . esc_html__( '暂无热门文章', 'neo-brutalism-blog' ) . '</p></li>';
			endif;
			?>
		</ul>
	</div>

	<!-- ==========================================
	     标签云
	     ========================================== -->
	<div class="sidebar-widget">
		<h3 class="sidebar-widget-title">
			<?php esc_html_e( '标签云', 'neo-brutalism-blog' ); ?>
		</h3>

		<div class="tag-cloud">
			<?php
			$tags = get_tags(
				array(
					'orderby' => 'count',
					'order'   => 'DESC',
					'number'  => 20,
				)
			);

			if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) :
				foreach ( $tags as $tag ) :
					?>
					<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-link">
						<?php echo esc_html( $tag->name ); ?>
					</a>
				<?php
				endforeach;
			endif;
			?>
		</div>
	</div>

	<!-- ==========================================
	     订阅面板（邮件订阅）
	     ========================================== -->
	<div class="newsletter-widget" id="newsletter">
		<div class="newsletter-widget-inner">
			<h3 class="newsletter-widget-title">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
				<?php esc_html_e( '订阅周刊', 'neo-brutalism-blog' ); ?>
			</h3>
			<p class="newsletter-desc">
				<?php esc_html_e( '每周精选前端技术文章，直达你的邮箱。', 'neo-brutalism-blog' ); ?>
			</p>
			<form class="newsletter-form" action="#" method="post" id="newsletter-form">
				<input
					type="email"
					name="email"
					class="newsletter-input"
					placeholder="your@email.com"
					required
					aria-label="<?php esc_attr_e( '输入你的邮箱地址', 'neo-brutalism-blog' ); ?>"
				/>
				<button type="submit" class="newsletter-submit" aria-label="<?php esc_attr_e( '订阅', 'neo-brutalism-blog' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
				</button>
			</form>
		</div>
	</div>

<?php endif; // end is_active_sidebar check ?>
