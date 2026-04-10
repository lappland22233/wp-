<?php
/**
 * 文章卡片模板片段
 *
 * 用于首页和归档页的文章列表网格中的单个卡片
 *
 * @package Neo_Brutalism_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : null;

// 作者信息
$author      = get_the_author_meta( 'display_name' );
$initial     = mb_substr( $author, 0, 1, 'UTF-8' );

// 评论数
$comments_count = get_comments_number();

// 特色图片 URL
$thumb_url = '';
if ( has_post_thumbnail() ) {
	$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'post-card-thumb' );
}
if ( empty( $thumb_url ) ) {
	$thumb_url = esc_url( NEO_BRUTALISM_URI . '/assets/default-post.svg' );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<!-- 卡片封面图 -->
	<div class="post-card-image-wrapper">
		<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'post-card-thumb', array( 'class' => 'post-card-image' ) ); ?>
			<?php else : ?>
				<img
					src="<?php echo esc_url( $thumb_url ); ?>"
					alt="<?php echo esc_attr( get_the_title() ); ?>"
					class="post-card-image"
					onerror="this.src='<?php echo esc_url( NEO_BRUTALISM_URI . '/assets/default-post.svg' ); ?>'"
					loading="lazy"
				/>
			<?php endif; ?>
		</a>

		<!-- 分类标签角标 -->
		<?php if ( $category ) : ?>
			<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-card-category-badge">
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php endif; ?>
	</div>

	<!-- 卡片正文 -->
	<div class="post-card-body">
		<!-- 元数据：日期 + 阅读时间 -->
		<div class="post-card-meta">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y-m-d' ) ); ?></time>
			<span class="post-card-meta-sep">&middot;</span>
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
			<?php echo esc_html( neo_brutalism_reading_time( get_the_ID() ) ); ?>
		</div>

		<!-- 标题 -->
		<h3 class="post-card-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

		<!-- 摘要 -->
		<p class="post-card-excerpt">
			<?php echo wp_kses_post( get_the_excerpt() ); ?>
		</p>

		<!-- 底部：作者 + 评论数 -->
		<div class="post-card-footer">
			<div class="author-info">
				<span class="post-card-author-avatar">
					<?php echo wp_kses_post( neo_brutalism_get_author_avatar( get_the_author_meta( 'ID' ) ) ); ?>
				</span>
				<span class="post-card-author-name"><?php echo esc_html( $author ); ?></span>
			</div>

			<div class="post-card-comments">
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L7.9 20Z"/><path d="M11.8 13.4L8 17.2"/><line x1="16" x2="19" y1="8" y2="11"/></svg>
				<span><?php echo esc_html( number_format_i18n( $comments_count ) ); ?></span>
			</div>
		</div>
	</div>
</article>
