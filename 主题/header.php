<?php
/**
 * 主题页头模板
 *
 * 包含 HTML <head> 标签、粘性导航栏（Logo/导航菜单/主题切换/移动端汉堡菜单）
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-container">
	<!-- ==========================================
	     粘性顶部导航栏
	     ========================================== -->
	<header id="site-header" class="site-header">
		<div class="header-inner">
			<!-- Logo / 网站标题 -->
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-branding">
						<span class="site-logo-icon">B</span>
						<span class="site-title">
							Blog<span class="accent">.</span>
						</span>
					</a>
				<?php endif; ?>
			</div>

			<!-- 桌面端主导航 -->
			<nav id="main-navigation" class="main-navigation" aria-label="<?php esc_attr_e( '主导航', 'neo-brutalism-blog' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'main-menu',
							'menu_id'        => 'primary-menu',
							'depth'          => 1,
							'walker'         => new Neo_Brutalism_Nav_Walker(),
							'fallback_cb'    => false,
						)
					);
				} else {
					// 默认导航链接（当没有设置自定义菜单时显示）
					echo '<ul class="main-menu" id="primary-menu">';
					$default_nav_items = array(
						array(
							'title' => esc_html__( '首页', 'neo-brutalism-blog' ),
							'url'   => home_url( '/' ),
						),
						array(
							'title' => esc_html__( '文章', 'neo-brutalism-blog' ),
							'url'   => home_url( '/?post_type=post' ),
						),
						array(
							'title' => esc_html__( '分类', 'neo-brutalism-blog' ),
							'url'   => home_url( '/?taxonomy=category' ),
						),
						array(
							'title' => esc_html__( '关于', 'neo-brutalism-blog' ),
							'url'   => home_url( '/?page_id=' . neo_brutalism_get_about_page_id() ),
						),
					);
					foreach ( $default_nav_items as $item ) {
						printf(
							'<li><a href="%s" class="nav-link">%s</a></li>',
							esc_url( $item['url'] ),
							esc_html( $item['title'] )
						);
					}
					echo '</ul>';
				}
				?>
			</nav>

			<!-- 操作按钮区域 -->
			<div class="header-actions">
				<!-- 主题切换按钮 -->
				<button
					type="button"
					class="theme-toggle-btn"
					id="theme-toggle"
					aria-label="<?php esc_attr_e( '切换明暗主题', 'neo-brutalism-blog' ); ?>"
				>
					<!-- 太阳图标（亮色模式显示） -->
					<svg
						xmlns="http://www.w3.org/2000/svg"
						width="20"
						height="20"
						viewBox="0 0 24 24"
						fill="none"
						stroke="currentColor"
						stroke-width="2"
						stroke-linecap="round"
						stroke-linejoin="round"
						class="theme-icon-sun"
						aria-hidden="true"
					>
						<circle cx="12" cy="12" r="4"></circle>
						<path d="M12 2v2"></path>
						<path d="M12 20v2"></path>
						<path d="m4.93 4.93 1.41 1.41"></path>
						<path d="m17.66 17.66 1.41 1.41"></path>
						<path d="M2 12h2"></path>
						<path d="M20 12h2"></path>
						<path d="m6.34 17.66-1.41 1.41"></path>
						<path d="m19.07 4.93-1.41 1.41"></path>
					</svg>
					<!-- 月亮图标（暗色模式显示） -->
					<svg
						xmlns="http://www.w3.org/2000/svg"
						width="20"
						height="20"
						viewBox="0 0 24 24"
						fill="none"
						stroke="currentColor"
						stroke-width="2"
						stroke-linecap="round"
						stroke-linejoin="round"
						class="theme-icon-moon"
						aria-hidden="true"
					>
						<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
					</svg>
					<span class="sr-only"><?php esc_html_e( '切换主题', 'neo-brutalism-blog' ); ?></span>
				</button>

				<!-- 移动端菜单按钮 -->
				<button
					type="button"
					class="mobile-menu-btn"
					id="mobile-menu-toggle"
					aria-label="<?php esc_attr_e( '打开菜单', 'neo-brutalism-blog' ); ?>"
					aria-expanded="false"
				>
					<!-- X 图标（菜单打开时显示，通过 CSS 切换） -->
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="menu-icon-close" style="display:none;" aria-hidden="true">
						<path d="M18 6 6 18"></path>
						<path d="m6 6 12 12"></path>
					</svg>
					<!-- 菜单图标（默认显示） -->
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="menu-icon-open" aria-hidden="true">
						<line x1="4" x2="20" y1="12" y2="12"></line>
						<line x1="4" x2="20" y1="6" y2="6"></line>
						<line x1="4" x2="20" y1="18" y2="18"></line>
					</svg>
				</button>

				<!-- 订阅按钮 -->
				<a href="#newsletter" class="subscribe-btn" id="subscribe-link">
					<?php esc_html_e( '订阅', 'neo-brutalism-blog' ); ?>
				</a>
			</div>
		</div><!-- .header-inner -->

		<!-- 移动端导航面板 -->
		<nav
			id="mobile-nav"
			class="mobile-nav"
			aria-label="<?php esc_attr_e( '移动端导航', 'neo-brutalism-blog' ); ?>"
			hidden
		>
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_class'     => '',
						'depth'          => 1,
						'walker'         => new Neo_Brutalism_Nav_Walker(),
						'fallback_cb'    => false,
					)
				);
			}
			?>
		</nav>
	</header><!-- #site-header -->

<?php
/**
 * 辅助函数：获取"关于"页面 ID
 */
function neo_brutalism_get_about_page_id() {
	$about_page = get_page_by_path( 'about' );
	if ( $about_page ) {
		return $about_page->ID;
	}
	return 0;
}
?>
