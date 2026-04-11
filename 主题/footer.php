<?php
/**
 * 主题页脚模板
 *
 * 三栏布局：品牌信息 / 导航链接 / 联系方式 + 版权信息
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<!-- ==========================================
	     页脚区域
	     ========================================== -->
	<footer id="site-footer" class="site-footer">
		<div class="footer-inner">
			<div class="footer-grid">
				<!-- 第一列：品牌介绍 -->
				<div class="footer-col footer-brand-col">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<div class="footer-brand">
							<span class="footer-brand-icon">B</span>
							<span class="footer-brand-name">
								Blog<span class="accent">.</span>
							</span>
						</div>
						<p class="footer-description">
							<?php echo esc_html( get_bloginfo( 'description' ) ?: __( '一个专注于前端技术与设计美学的博客，记录每一次思考与成长。', 'neo-brutalism-blog' ) ); ?>
						</p>
					<?php endif; ?>
				</div>

				<!-- 第二列：导航链接 -->
				<div class="footer-col footer-nav-col">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php else : ?>
						<h4 class="footer-heading"><?php esc_html_e( '导航', 'neo-brutalism-blog' ); ?></h4>
						<nav class="footer-links" aria-label="<?php esc_attr_e( '页脚导航', 'neo-brutalism-blog' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '首页', 'neo-brutalism-blog' ); ?></a>
							<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/?post_type=post' ) ); ?>"><?php esc_html_e( '文章归档', 'neo-brutalism-blog' ); ?></a>
							<?php
								// 显示分类列表
								$categories = get_categories(
									array(
										'orderby' => 'count',
										'order'   => 'DESC',
										'number'  => 5,
									)
								);
								if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
							?>
								<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php esc_html_e( '分类浏览', 'neo-brutalism-blog' ); ?></a>
							<?php endif; ?>
							<?php
								$about_page = get_page_by_path( 'about' );
								if ( $about_page ) :
							?>
								<a href="<?php echo esc_url( get_permalink( $about_page->ID ) ); ?>"><?php esc_html_e( '关于作者', 'neo-brutalism-blog' ); ?></a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
				</div>

				<!-- 第三列：联系方式 -->
				<div class="footer-col footer-contact-col">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<h4 class="footer-heading"><?php esc_html_e( '联系', 'neo-brutalism-blog' ); ?></h4>
						<div class="footer-contact">
							<a href="mailto:lappland@krsi.top">lappland@krsi.top</a>
							<a href="https://github.com/lappland22233" target="_blank" rel="noopener noreferrer">GitHub</a>
						</div>
					<?php endif; ?>
				</div>
			</div><!-- .footer-grid -->

			<hr class="footer-divider" />

			<!-- 版权信息栏 -->
			<div class="footer-bottom">
				<p>&copy; <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'neo-brutalism-blog' ); ?></p>
				<p><?php printf( esc_html__( 'Built with %s + Neo-Brutalism Design', 'neo-brutalism-blog' ), 'WordPress' ); ?></p>
			</div>
		</div><!-- .footer-inner -->
	</footer><!-- #site-footer -->

</div><!-- .site-container -->

<?php wp_footer(); ?>

</body>
</html>
