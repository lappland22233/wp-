<?php
/**
 * 无内容时显示的模板片段
 *
 * 当没有找到任何文章时显示
 *
 * @package Neo_Brutalism_Blog
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h2 class="posts-section-title"><?php esc_html_e( '暂无文章', 'neo-brutalism-blog' ); ?></h2>
	</header>
	<div class="page-content" style="text-align: center; padding: 3rem 1rem;">
		<p><?php esc_html_e( '目前还没有发布任何文章。请稍后再来看看！', 'neo-brutalism-blog' ); ?></p>
		<?php if ( current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: 1: 新文章编辑器链接 */
						__( '准备好发布你的第一篇文章了吗？ <a href="%1$s">开始撰写</a>。', 'neo-brutalism-blog' ),
						array( 'a' => array( 'href' => array() ) )
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php endif; ?>
	</div>
</section>
