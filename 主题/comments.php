<?php
/**
 * 评论模板
 *
 * 显示评论列表和评论表单
 *
 * @package Neo_Brutalism_Blog
 * @var WP_Comment[] $comments 评论对象数组
 * @var string      $comment_args 评论参数
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( '1' === $comment_count ) {
				printf(
					/* translators: 1: 评论数 */
					esc_html( _nx( '%s 条评论', '%s 条评论', $comment_count, '评论标题', 'neo-brutalism-blog' ) ),
					number_format_i18n( $comment_count )
				);
			} else {
				printf(
					/* translators: 1: 评论数 */
					esc_html( _nx( '%s 条评论', '%s 条评论', $comment_count, '评论标题', 'neo-brutalism-blog' ) ),
					number_format_i18n( $comment_count )
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback'    => 'neo_brutalism_comment_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<!-- 评论分页 -->
		<?php
		$comment_pagination_args = array(
			'echo'           => true,
			'before'         => '<nav class="comment-pagination pagination">',
			'after'          => '</nav>',
		);

		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<nav class="comment-pagination pagination">
				<div class="comment-pagination-prev">
					<?php previous_comments_link( esc_html__( '&laquo; 较早的评论', 'neo-brutalism-blog' ) ); ?>
				</div>
				<div class="comment-pagination-next">
					<?php next_comments_link( esc_html__( '较新的评论 &raquo;', 'neo-brutalism-blog' ) ); ?>
				</div>
			</nav>
		<?php endif; ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
	// 如果评论已关闭且有评论，则显示提示
	if ( ! comments_open() && post_type_supports( get_post_type(), 'comments') ) :
		?>
		<p class="no-comments" style="text-align: center; padding: 2rem; color: var(--color-muted-foreground);">
			<?php esc_html_e( '评论已关闭。', 'neo-brutalism-blog' ); ?>
		</p>
	<?php endif; ?>

	<!-- 评论表单 -->
	<?php
	comment_form(
		array(
			'title_reply'        => __( '发表评论', 'neo-brutalism-blog' ),
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h3>',
			'class_form'         => 'comment-form',
			'class_submit'       => 'submit-comment-btn',
			'label_submit'       => __( '提交评论', 'neo-brutalism-blog' ),
		)
	);
	?>

</div><!-- #comments -->

<?php
/**
 * 自定义评论输出回调函数
 *
 * 自定义每条评论的 HTML 输出样式以匹配主题设计
 *
 * @param WP_Comment $comment 评论对象。
 * @param array      $args    参数数组。
 * @param int        $depth   当前深度。
 */
function neo_brutalism_comment_callback( $comment, $args, $depth ) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<?php endif; ?>

		<div class="comment-body">
			<!-- 评论者头像 -->
			<div class="comment-avatar">
				<?php
				if ( $args['avatar_size'] != 0 ) {
					echo get_avatar( $comment, $args['avatar_size'] );
				}
				?>
			</div>

			<!-- 评论内容和元数据 -->
			<div class="comment-content">
				<!-- 作者名 + 日期 -->
				<div class="comment-meta">
					<cite class="comment-author"><?php echo get_comment_author_link(); ?></cite>

					<time datetime="<?php comment_time( 'c' ); ?>" class="comment-date">
						<?php
						/* translators: 1: 日期, 2: 时间 */
						printf(
							esc_html__( '%1$s at %2$s', 'neo-brutalism-blog' ),
							get_comment_date(),
							get_comment_time()
						);
						?>
					</time>
				</div>

				<!-- 评论正文 -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( '您的评论正在等待审核。', 'neo-brutalism-blog' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-text"><?php comment_text(); ?></div>

				<!-- 回复链接 -->
				<div class="reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '',
								'after'     => '',
							)
						)
					);
					?>
				</div>
			</div>
		</div>

	<?php if ( 'div' != $args['style'] ) : ?>
		</div><!-- .comment-body -->
	<?php endif;
}
?>
