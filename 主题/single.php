<?php
/**
 * 单篇文章详情页模板
 *
 * 显示：特色图片大图 → 文章标题 → 元信息 → 正文内容 → 评论区 → 文章导航
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

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
				<!-- ==========================================
				     文章头部：特色图片 + 标题 + 元数据
				     ========================================== -->
				<header class="single-post-header">
					<!-- 特色图片 -->
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-post-thumbnail">
							<?php the_post_thumbnail( 'featured-large' ); ?>
						</div>
					<?php endif; ?>

					<!-- 文章标题 -->
					<h1 class="single-post-title"><?php the_title(); ?></h1>

					<!-- 元数据栏：分类 / 作者 / 日期 / 阅读时间 / 评论数 -->
					<div class="single-post-meta-bar">
						<!-- 分类标签 -->
						<?php
						$categories = get_the_category();
						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
							foreach ( $categories as $cat ) :
								?>
								<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="category-badge" rel="category tag">
									<?php echo esc_html( $cat->name ); ?>
								</a>
							<?php
							endforeach;
						endif;
						?>

						<!-- 作者 + 头像 -->
						<span class="author-info">
							<span class="author-avatar">
								<?php echo wp_kses_post( neo_brutalism_get_author_avatar( get_the_author_meta( 'ID' ) ) ); ?>
							</span>
							<span class="author-name"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></span>
						</span>

						<!-- 发布日期 -->
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
							<?php echo esc_html( get_the_date() ); ?>
						</time>

						<!-- 阅读时间 -->
						<span style="color: var(--color-muted-foreground); font-size: 0.875rem;">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
							<?php echo esc_html( neo_brutalism_reading_time( get_the_ID() ) ); ?>
						</span>
					</div>
				</header>

				<!-- ==========================================
				     文章正文内容
				     ========================================== -->
				<div class="single-post-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( '文章分页', 'neo-brutalism-blog' ) . '"><span class="page-links-label">' . __( '页面:', 'neo-brutalism-blog' ) . '</span>',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<!-- 文章底部元信息（标签） -->
				<footer class="entry-footer" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--color-border);">
					<?php
					$tags_list = get_the_tag_list( '<span class="tag-cloud">', '</span><span class="tag-link">', '</span>' );
					if ( $tags_list ) :
						?>
						<strong style="font-size: 0.875rem; margin-right: 0.5rem;"><?php _e( '标签:', 'neo-brutalism-blog' ); ?></strong>
						<div class="tag-cloud" style="display: inline-flex; flex-wrap: wrap;">
							<?php echo wp_kses_post( $tags_list ); ?>
						</div>
					<?php endif; ?>
				</footer>
			</article><!-- #post-<?php the_ID(); ?> -->

			<!-- ==========================================
			     上一篇/下一篇导航
			     ========================================== -->
			<nav class="post-navigation" aria-label="<?php esc_attr_e( '文章导航', 'neo-brutalism-blog' ); ?>">
				<?php
				$prev_post = get_previous_post();
				$next_post = get_next_post();
				?>

				<?php if ( ! empty( $prev_post ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="post-nav-link">
						<span class="post-nav-label"><?php esc_html_e( '上一篇', 'neo-brutalism-blog' ); ?></span>
						<span class="post-nav-title"><?php echo esc_html( wp_trim_words( $prev_post->post_title, 8, '...' ) ); ?></span>
					</a>
				<?php else : ?>
					<div class="post-nav-link" style="opacity: 0.5;">
						<span class="post-nav-label"><?php esc_html_e( '上一篇', 'neo-brutalism-blog' ); ?></span>
						<span class="post-nav-title"><?php esc_html_e( '没有更早的文章了', 'neo-brutalism-blog' ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $next_post ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="post-nav-link" style="text-align: right;">
						<span class="post-nav-label"><?php esc_html_e( '下一篇', 'neo-brutalism-blog' ); ?></span>
						<span class="post-nav-title"><?php echo esc_html( wp_trim_words( $next_post->post_title, 8, '...' ) ); ?></span>
					</a>
				<?php else : ?>
					<div class="post-nav-link" style="opacity: 0.5; text-align: right;">
						<span class="post-nav-label"><?php esc_html_e( '下一篇', 'neo-brutalism-blog' ); ?></span>
						<span class="post-nav-title"><?php esc_html_e( '没有更新的文章了', 'neo-brutalism-blog' ); ?></span>
					</div>
				<?php endif; ?>
			</nav>

			<!-- ==========================================
			     评论区
			     ========================================== -->
			<?php
			// 如果评论开启或已有评论，则加载评论模板
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; // End of the loop. ?>
	</main><!-- #site-main -->

<?php
get_footer();
