<?php
/**
 * 精选文章模板片段
 *
 * 双栏大卡片布局：左侧封面图 + 右侧标题/摘要/作者信息
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : null;

// 作者信息
$author = get_the_author_meta( 'display_name' );

// 预览图 URL：特色图 > 正文第一张图 > 外部图源
$thumb_url = neo_brutalism_get_post_preview_image( get_the_ID(), 'post-card-thumb' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'featured-post' ); ?>>
	<!-- 封面图 -->
	<div class="featured-post-image-wrapper">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<img
					src="<?php echo esc_url( $thumb_url ); ?>"
					alt="<?php echo esc_attr( get_the_title() ); ?>"
					class="featured-post-image"
					onerror="this.src='<?php echo esc_url( NEO_BRUTALISM_URI . '/assets/default-post.svg' ); ?>'"
					loading="eager"
					fetchpriority="high"
					decoding="async"
				/>
			</a>

		<span class="featured-badge"><?php esc_html_e( '精选', 'neo-brutalism-blog' ); ?></span>
	</div>

	<!-- 文章信息 -->
	<div class="featured-post-content">
		<!-- 元数据行：分类 + 日期 -->
		<div class="post-meta">
			<?php if ( $category ) : ?>
				<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="category-badge">
					<?php echo esc_html( $category->name ); ?>
				</a>
			<?php endif; ?>
			<span class="post-meta-date">
				<?php echo esc_html( get_the_date( 'Y-m-d' ) ); ?> &middot; <?php echo esc_html( neo_brutalism_reading_time( get_the_ID() ) ); ?> <?php esc_html_e( '阅读', 'neo-brutalism-blog' ); ?>
			</span>
		</div>

		<!-- 标题 -->
		<h2 class="featured-post-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<!-- 摘要 -->
		<p class="featured-post-excerpt">
			<?php echo wp_kses_post( get_the_excerpt() ); ?>
		</p>

		<!-- 底部：作者 + 阅读全文链接 -->
		<div class="featured-post-footer">
			<div class="author-info">
				<span class="author-avatar">
					<?php echo wp_kses_post( neo_brutalism_get_author_avatar( get_the_author_meta( 'ID' ) ) ); ?>
				</span>
				<span class="author-name"><?php echo esc_html( $author ); ?></span>
			</div>

			<a href="<?php the_permalink(); ?>" class="read-more-link">
				<?php esc_html_e( '阅读全文', 'neo-brutalism-blog' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
			</a>
		</div>
	</div>
</article>
