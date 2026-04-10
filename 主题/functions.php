<?php
/**
 * Neo-Brutalism Blog 主题功能与初始化
 *
 * @package Neo_Brutalism_Blog
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 防止直接访问
}

/**
 * 主题设置常量定义
 */
if ( ! defined( 'NEO_BRUTALISM_VERSION' ) ) {
	define( 'NEO_BRUTALISM_VERSION', '1.0.0' );
}

if ( ! defined( 'NEO_BRUTALISM_DIR' ) ) {
	define( 'NEO_BRUTALISM_DIR', get_template_directory() );
}

if ( ! defined( 'NEO_BRUTALISM_URI' ) ) {
	define( 'NEO_BRUTALISM_URI', get_template_directory_uri() );
}

/**
 * =====================================================
 * 1. 主题初始化设置
 * =====================================================
 */
if ( ! function_exists( 'neo_brutalism_setup' ) ) :
	/**
	 * 设置主题默认特性和支持功能
	 *
	 * @return void
	 */
	function neo_brutalism_setup() {
		// 让 WordPress 管理 <title> 标签
		add_theme_support( 'title-tag' );

		// 启用特色图片（缩略图）
		add_theme_support( 'post-thumbnails' );

		// 定义自定义缩略图尺寸
		add_image_size( 'post-card-thumb', 800, 500, true );      // 文章卡片封面
		add_image_size( 'featured-large', 1200, 750, true );       // 精选文章大图

		// 支持 HTML5 标记
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// 自定义 Logo 支持
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 60,
				'width'       => 200,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		// 响应式嵌入内容
		add_theme_support( 'responsive-embeds' );

		// 文章格式支持
		add_theme_support(
			'post-formats',
			array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video' )
		);

		// 选择性刷新小工具
		add_theme_support( 'customize-selective-refresh-widgets' );

		// 注册导航菜单位置
		register_nav_menus(
			array(
				'primary' => esc_html__( '主导航菜单', 'neo-brutalism-blog' ),
				'footer'  => esc_html__( '页脚菜单', 'neo-brutalism-blog' ),
			)
		);

		// 设置内容宽度
		$GLOBALS['content_width'] = 1200;
	}
endif;
add_action( 'after_setup_theme', 'neo_brutalism_setup' );


/**
 * =====================================================
 * 2. 脚本和样式入队
 * ===================================================== */

/**
 * 注册和加载前端资源
 *
 * @return void
 */
function neo_brutalism_scripts() {
	// 加载 Google Fonts（Poppins + Lora + Fira Code）
	wp_enqueue_style(
		'neo-brutalism-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Lora:wght@600;700&family=Fira+Code:wght@400;500&display=swap',
		array(),
		null // 不使用版本号缓存字体
	);

	// 加载主题主样式表
	wp_enqueue_style(
		'neo-brutalism-style',
		get_stylesheet_uri(),
		array( 'neo-brutalism-fonts' ),
		NEO_BRUTALISM_VERSION
	);

	// 加载主题交互脚本（暗色模式、移动端菜单等）
	wp_enqueue_script(
		'neo-brutalism-theme',
		get_template_directory_uri() . '/js/theme.js',
		array(),
		NEO_BRUTALISM_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	// 如果存在评论且用户已登录，加载评论回复脚本
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// 将 AJAX URL 等数据传递给 JS
	wp_localize_script(
		'neo-brutalism-theme',
		'neoBrutalismData',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'themePath' => NEO_BRUTALISM_URI,
			'isRTL'     => is_rtl() ? 'true' : 'false',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'neo_brutalism_scripts' );

/**
 * 在 head 中预加载关键资源
 *
 * @return void
 */
function neo_brutalism_preload_resources() {
	// 预连接到字体源
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
}
add_action( 'wp_head', 'neo_brutalism_preload_resources', 1 );


/**
 * =====================================================
 * 3. 小工具区域注册
 * ===================================================== */

/**
 * 注册侧边栏小工具区域
 *
 * @return void
 */
function neo_brutalism_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( '博客侧边栏', 'neo-brutalism-blog' ),
			'id'            => 'blog-sidebar',
			'description'   => esc_html__( '拖放小工具到这里以显示在博客侧边栏。', 'neo-brutalism-blog' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="sidebar-widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( '页脚第一列', 'neo-brutalism-blog' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( '页脚左侧区域的小工具。', 'neo-brutalism-blog' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-heading">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( '页脚第二列', 'neo-brutalism-blog' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( '页脚中间区域的小工具。', 'neo-brutalism-blog' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-heading">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( '页脚第三列', 'neo-brutalism-blog' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( '页脚右侧区域的小工具。', 'neo-brutalism-blog' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-heading">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'neo_brutalism_widgets_init' );


/**
 * =====================================================
 * 4. 自定义函数
 * ===================================================== */


/**
 * 获取文章预览图 URL。
 * 优先级：特色图 > 正文第一张图 > 外部随机图接口。
 *
 * @param int    $post_id 文章 ID。
 * @param string $size    缩略图尺寸。
 * @return string
 */
function neo_brutalism_get_post_preview_image( $post_id = 0, $size = 'post-card-thumb' ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( has_post_thumbnail( $post_id ) ) {
		$thumbnail_url = get_the_post_thumbnail_url( $post_id, $size );
		if ( ! empty( $thumbnail_url ) ) {
			return $thumbnail_url;
		}
	}

	$content = get_post_field( 'post_content', $post_id );
	if ( ! empty( $content ) && preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches ) ) {
		return $matches[1];
	}

	return 'https://api.lappland.top';
}

/**
 * 获取作者头像字母回退
 * 当没有设置头像时显示作者名字首字
 *
 * @param int $user_id 用户 ID。
 * @return string 头像 HTML
 */
function neo_brutalism_get_author_avatar( $user_id = 0 ) {
	$avatar = get_avatar( $user_id, 96 );

	if ( empty( $avatar ) || strpos( $avatar, 'avatar-default' ) !== false || strpos( $avatar, 'gravatar.com' ) === false ) {
		$author = get_userdata( $user_id );
		$name   = $author ? $author->display_name : '?';
		$initial = mb_substr( $name, 0, 1, 'UTF-8' );

		return sprintf(
			'<span class="author-avatar">%s</span>',
			esc_html( $initial )
		);
	}

	return $avatar;
}

/**
 * 估算文章阅读时间
 *
 * @param int $post_id 文章 ID。
 * @return string 阅读时间字符串
 */
function neo_brutalism_reading_time( $post_id = 0 ) {
	$content = get_post_field( 'post_content', $post_id );
	// 移除 HTML 标签后计算纯文本字数
	$text    = strip_tags( strip_shortcodes( $content ) );
	// 中英文混合字数统计：中文字符按1字计，英文单词按词计
	$cn_count = preg_match_all( '/[\x{4e00}-\x{9fff}]/u', $text, $_ );
	$en_words = str_word_count( preg_replace( '/[\x{4e00}-\x{9fff}]/u', '', $text ) );
	$total    = $cn_count + $en_words;

	// 平均阅读速度：中文约 500 字/分钟，英文约 250 词/分钟
	$minutes = ceil( ( $cn_count / 500 ) + ( $en_words / 250 ) );

	/* translators: %s: 分钟数 */
	return sprintf( esc_html__( '%s min', 'neo-brutalism-blog' ), number_format_i18n( $minutes ) );
}

/**
 * 获取精选文章（通过 sticky posts 或自定义字段实现）
 *
 * @param int  $count                要获取的文章数量。
 * @param bool $strict_featured_only 是否仅返回真实精选来源（自定义精选或 sticky）。
 * @return WP_Query
 */
function neo_brutalism_get_featured_posts( $count = 1, $strict_featured_only = false ) {
	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_is_featured',
		'meta_value'          => '1',
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	// 如果没有标记为精选的文章，则使用最新置顶文章作为备选
	$featured_query = new WP_Query( $args );

	if ( ! $featured_query->have_posts() ) {
		$sticky = get_option( 'sticky_posts' );
		if ( ! empty( $sticky ) ) {
			$args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $count,
				'post__in'            => $sticky,
				'ignore_sticky_posts' => true,
				'orderby'             => 'date',
				'order'               => 'DESC',
			);
		} elseif ( ! $strict_featured_only ) {
			$args = array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
		}

		$featured_query = new WP_Query( $args );
	}

	return $featured_query;
}

/**
 * 获取热门文章（按评论数量排序）
 *
 * @param int $count 文章数量。
 * @return WP_Query
 */
function neo_brutalism_get_trending_posts( $count = 5 ) {
	return new WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'orderby'        => 'comment_count',
			'order'          => 'DESC',
		)
	);
}



/**
 * 统一博客列表排序：按发布时间倒序（最新在前）。
 *
 * @param WP_Query $query 查询对象。
 * @return void
 */
function neo_brutalism_sort_posts_by_date_desc( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() || $query->is_archive() || $query->is_search() ) {
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'neo_brutalism_sort_posts_by_date_desc' );

/**
 * =====================================================
 * 5. 导航 Walker（自定义导航输出）
 * ===================================================== */

/**
 * 自定义导航 Walker 类
 * 用于生成带有下划线滑入效果的导航链接
 */
class Neo_Brutalism_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * 开始输出元素
	 *
	 * @param string $output 用于追加输出的 HTML。
	 * @param object $item   当前菜单项对象。
	 * @param int    $depth  菜单项深度。
	 * @param array  $args   参数数组。
	 * @param int    $id     当前元素 ID。
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$item_classes = is_array( $item->classes ) ? $item->classes : array();
		$class_names  = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $item_classes ), $item, $args, $depth ) );

		$link_classes = 'nav-link';
		if ( in_array( 'current-menu-item', $item_classes, true ) || in_array( 'current-page-ancestor', $item_classes, true ) ) {
			$link_classes .= ' current-page';
		}

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = $link_classes;

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


/**
 * =====================================================
 * 6. 自定义文章类型支持（可选扩展）
 * ===================================================== */

/**
 * 在编辑界面添加"设为精选"元框
 *
 * @return void
 */
function neo_brutalism_add_featured_meta_box() {
	add_meta_box(
		'neo_brutalism_featured',
		esc_html__( '文章设置', 'neo-brutalism-blog' ),
		'neo_brutalism_featured_meta_box_callback',
		'post',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'neo_brutalism_add_featured_meta_box' );

/**
 * 精选文章元框回调函数
 *
 * @param WP_Post $post 当前文章对象。
 * @return void
 */
function neo_brutalism_featured_meta_box_callback( $post ) {
	wp_nonce_field( 'neo_brutalism_featured_save', 'neo_brutalism_featured_nonce' );

	$is_featured = get_post_meta( $post->ID, '_is_featured', true );
	?>
	<p>
		<label>
			<input type="checkbox" name="_is_featured" value="1" <?php checked( $is_featured, '1'); ?> />
			<strong><?php esc_html_e( '设为精选文章', 'neo-brutalism-blog' ); ?></strong>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( '精选文章会以更大的样式展示在首页顶部。', 'neo-brutalism-blog' ); ?>
	</p>
	<?php
}

/**
 * 保存精选文章设置
 *
 * @param int $post_id 文章 ID。
 * @return void
 */
function neo_brutalism_save_featured_meta( $post_id ) {
	if ( ! isset( $_POST['neo_brutalism_featured_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['neo_brutalism_featured_nonce'] ) ), 'neo_brutalism_featured_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_featured = isset( $_POST['_is_featured'] ) && '1' === $_POST['_is_featured'] ? '1' : '';

	update_post_meta( $post_id, '_is_featured', $is_featured );
}
add_action( 'save_post', 'neo_brutalism_save_featured_meta' );


/**
 * =====================================================
 * 7. 辅助过滤器
 * ===================================================== */

/**
 * 添加 body 类名用于暗色模式检测
 *
 * @param array $classes 现有类名。
 * @return array 更新后的类名
 */
function neo_brutalism_body_classes( $classes ) {
	// 添加单栏/双栏布局类
	if ( ! is_active_sidebar( 'blog-sidebar' ) ) {
		$classes[] = 'no-sidebar';
	}

	// 添加首页标识
	if ( is_front_page() || is_home() ) {
		$classes[] = 'blog-index';
	}

	// 添加单篇文章标识
	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-post-view';
	}

	return $classes;
}
add_filter( 'body_class', 'neo_brutalism_body_classes' );

/**
 * 自定义摘要长度
 *
 * @param int $length 默认摘要长度。
 * @return int 修改后的长度
 */
function neo_brutalism_excerpt_length( $length ) {
	return 35; // 约 140 个中文字符
}
add_filter( 'excerpt_length', 'neo_brutalism_excerpt_length' );

/**
 * 自定义摘要结尾的省略号
 *
 * @param string $more 默认的 "..." 字符串。
 * @return string 修改后的省略号
 */
function neo_brutalism_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'neo_brutalism_excerpt_more' );

/**
 * 排除特定分类/标签在搜索结果中的处理
 * 可根据需求扩展
 */


/**
 * =====================================================
 * 8. 安全增强
 * ===================================================== */

/**
 * 从输出中移除 WordPress 版本号
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * 禁止 REST API 向未认证用户暴露用户列表
 *
 * @param array $endpoint REST API 端点。
 * @return array 过滤后的端点
 */
function neo_brutalism_rest_endpoints( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'neo_brutalism_rest_endpoints' );


/**
 * =====================================================
 * 9. 主题激活时的操作
 * ===================================================== */

/**
 * 主题激活时创建一些默认选项或执行一次性任务
 */
function neo_brutalism_activate() {
	// 可以在这里添加首次安装时需要执行的代码
}
add_action( 'after_switch_theme', 'neo_brutalism_activate' );
