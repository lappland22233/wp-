<?php
/**
 * 404 错误页模板
 *
 * 当请求的页面不存在时显示此模板
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

	<main id="site-main" class="site-main">
		<section class="error-404">
			<div class="error-404-code">404</div>

			<h1 class="error-404-title"><?php esc_html_e( '页面未找到', 'neo-brutalism-blog' ); ?></h1>

			<p class="error-404-message">
				<?php esc_html_e( '抱歉，您访问的页面不存在或已被移除。可能输入错了地址，或者该页面已经被转移到新的位置。', 'neo-brutalism-blog' ); ?>
			</p>

			<!-- 返回首页按钮 -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-home-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
				<?php esc_html_e( '返回首页', 'neo-brutalism-blog' ); ?>
			</a>

			<!-- 可选：显示搜索框 -->
			<div class="sidebar-widget" style="max-width: 400px; margin: 2rem auto 0;">
				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="search-input-404" class="sr-only"><?php esc_attr_e( '搜索文章', 'neo-brutalism-blog' ); ?></label>
					<input
						type="search"
						id="search-input-404"
						class="search-form-input"
						placeholder="<?php esc_attr_e( '尝试搜索...', 'neo-brutalism-blog' ); ?>"
						value=""
						name="s"
						autocomplete="off"
					/>
					<button type="submit" class="search-form-button" aria-label="<?php esc_attr_e( '搜索', 'neo-brutalism-blog' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
					</button>
				</form>
			</div>
		</section>
	</main><!-- #site-main -->

<?php
get_footer();
