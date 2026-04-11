/**
 * Neo-Brutalism Blog 主题交互脚本
 *
 * 功能：
 * - 暗色/亮色主题切换（localStorage 持久化）
 * - 移动端导航菜单展开/收起
 * - 平滑滚动增强
 *
 * @package lappland22233
 * @version 1.0.2
 */

(function () {
	'use strict';

	// =====================================================
	// 1. 主题初始化与暗色模式管理
	// =====================================================

	const THEME_KEY = 'neo-brutalism-theme'; // localStorage 存储键名
	const DARK_CLASS = 'dark';

	/**
	 * 获取当前主题偏好
	 * 优先级：localStorage > 系统偏好 > 默认亮色
	 */
	function getPreferredTheme() {
		try {
			const stored = localStorage.getItem(THEME_KEY);
			if (stored === 'dark' || stored === 'light') {
				return stored;
			}
		} catch (e) {
			// localStorage 可能不可用，静默忽略
		}

		// 回退到系统偏好
		if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			return 'dark';
		}

		return 'light';
	}

	/**
	 * 应用主题到 <body> 元素
	 */
	function applyTheme(theme) {
		if (theme === 'dark') {
			document.body.classList.add(DARK_CLASS);
		} else {
			document.body.classList.remove(DARK_CLASS);
		}
	}

	/**
	 * 切换主题（在 dark/light 之间）
	 */
	function toggleTheme() {
		const currentTheme = document.body.classList.contains(DARK_CLASS) ? 'dark' : 'light';
		const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

		applyTheme(newTheme);

		try {
			localStorage.setItem(THEME_KEY, newTheme);
		} catch (e) {
			// 静默忽略存储失败
		}

		// 触发自定义事件供其他脚本监听
		window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
	}

	// 初始化时应用保存的主题
	applyTheme(getPreferredTheme());

	// 绑定主题切换按钮
	var themeToggleBtn = document.getElementById('theme-toggle');
	if (themeToggleBtn) {
		themeToggleBtn.addEventListener('click', function () {
			toggleTheme();
		});
	}

	// 监听系统主题变化（用户在操作系统层面切换时自动同步）
	if (window.matchMedia) {
		try {
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
				// 仅当用户没有手动设置过主题时才跟随系统
				try {
					var stored = localStorage.getItem(THEME_KEY);
					if (!stored) {
						applyTheme(e.matches ? 'dark' : 'light');
					}
				} catch (err) {
					// 静默忽略
				}
			});
		} catch (e) {
			// 某些旧浏览器可能不支持 addEventListener on matchMedia
		}
	}


	// =====================================================
	// 2. 移动端导航菜单
	// =====================================================

	var mobileMenuToggle = document.getElementById('mobile-menu-toggle');
	var mobileNav = document.getElementById('mobile-nav');

	/**
	 * 切换移动端导航菜单的展开/收起状态
	 */
	function toggleMobileMenu() {
		if (!mobileNav || !mobileMenuToggle) return;

		var isOpen = mobileNav.classList.contains('is-open');

		if (isOpen) {
			closeMobileMenu();
		} else {
			openMobileMenu();
		}
	}

	/**
	 * 打开移动端导航菜单
	 */
	function openMobileMenu() {
		mobileNav.classList.add('is-open');
		mobileNav.hidden = false;
		mobileMenuToggle.setAttribute('aria-expanded', 'true');

		// 切换图标：显示 X，隐藏汉堡
		var iconOpen = mobileMenuToggle.querySelector('.menu-icon-open');
		var iconClose = mobileMenuToggle.querySelector('.menu-icon-close');

		if (iconOpen) iconOpen.style.display = 'none';
		if (iconClose) iconClose.style.display = 'block';
	}

	/**
	 * 关闭移动端导航菜单
	 */
	function closeMobileMenu() {
		mobileNav.classList.remove('is-open');
		mobileNav.hidden = true;
		mobileMenuToggle.setAttribute('aria-expanded', 'false');

		// 切换图标：显示汉堡，隐藏 X
		var iconOpen = mobileMenuToggle.querySelector('.menu-icon-open');
		var iconClose = mobileMenuToggle.querySelector('.menu-icon-close');

		if (iconOpen) iconOpen.style.display = 'block';
		if (iconClose) iconClose.style.display = 'none';
	}

	// 绑定移动端菜单按钮点击事件
	if (mobileMenuToggle) {
		mobileMenuToggle.addEventListener('click', toggleMobileMenu);
	}

	// 点击移动端导航链接后自动关闭菜单
	if (mobileNav) {
		var navLinks = mobileNav.querySelectorAll('a');
		for (var i = 0; i < navLinks.length; i++) {
			navLinks[i].addEventListener('click', closeMobileMenu);
		}
	}

	// 点击页面其他区域时关闭移动端菜单
	document.addEventListener('click', function (event) {
		if (!mobileNav || !mobileMenuToggle) return;

		var isOpen = mobileNav.classList.contains('is-open');
		if (!isOpen) return;

		// 如果点击目标不在菜单或按钮内部，则关闭
		var target = event.target;
		var isInsideMenu = mobileNav.contains(target);
		var isToggleButton = mobileMenuToggle.contains(target);

		if (!isInsideMenu && !isToggleButton) {
			closeMobileMenu();
		}
	});

	// ESC 键关闭菜单
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' || event.keyCode === 27) {
			if (mobileNav && mobileNav.classList.contains('is-open')) {
				closeMobileMenu();
				mobileMenuToggle.focus();
			}
		}
	});


	// =====================================================
	// 3. 平滑滚动增强
	// =====================================================

	// 为所有锚点链接添加平滑滚动
	var anchorLinks = document.querySelectorAll('a[href^="#"]');

	for (var j = 0; j < anchorLinks.length; j++) {
		anchorLinks[j].addEventListener('click', function (e) {
			var targetId = this.getAttribute('href');
			if (targetId === '#' || targetId === '') return;

			var targetEl = document.querySelector(targetId);
			if (targetEl) {
				e.preventDefault();

				// 计算粘性头部高度偏移
				var header = document.getElementById('site-header');
				var headerHeight = header ? header.offsetHeight : 0;

				var targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

				window.scrollTo({
					top: targetPosition,
					behavior: 'smooth'
				});

				// 更新 URL hash 但不触发跳转
				if (history.pushState) {
					history.pushState(null, null, targetId);
				}
			}
		});
	}


	// =====================================================
	// 4. 固定分页导航显示控制
	// =====================================================

	var fixedPagination = document.getElementById('fixed-pagination');
	if (fixedPagination) {
		var lastScrollTop = window.scrollY || 0;
		var isPaginationTicking = false;
		var topThreshold = 200;
		var bottomThreshold = 120;

		function updateFixedPaginationVisibility() {
			var currentScrollTop = window.scrollY || 0;
			var viewportBottom = currentScrollTop + window.innerHeight;
			var documentBottom = document.documentElement.scrollHeight;
			var distanceToBottom = documentBottom - viewportBottom;
			var isScrollingDown = currentScrollTop > lastScrollTop && currentScrollTop > topThreshold;
			var isNearBottom = distanceToBottom <= bottomThreshold;

			if (isScrollingDown || isNearBottom) {
				fixedPagination.classList.remove('pagination-hidden');
				fixedPagination.classList.add('pagination-visible');
			} else {
				fixedPagination.classList.remove('pagination-visible');
				fixedPagination.classList.add('pagination-hidden');
			}

			lastScrollTop = currentScrollTop;
			isPaginationTicking = false;
		}

		window.addEventListener('scroll', function () {
			if (isPaginationTicking) return;
			window.requestAnimationFrame(updateFixedPaginationVisibility);
			isPaginationTicking = true;
		}, { passive: true });

		window.addEventListener('resize', function () {
			window.requestAnimationFrame(updateFixedPaginationVisibility);
		});

		updateFixedPaginationVisibility();
	}


	// =====================================================
	// 5. 订阅表单处理（前端验证）
	// =====================================================

	var newsletterForm = document.getElementById('newsletter-form');
	if (newsletterForm) {
		newsletterForm.addEventListener('submit', function (e) {
			e.preventDefault();

			var emailInput = newsletterForm.querySelector('input[name="email"]');
			if (!emailInput) return;

			var email = emailInput.value.trim();

			// 简单的邮箱格式校验
			var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(email)) {
				emailInput.style.borderColor = 'oklch(0.7091 0.1697 21.9551)';
				emailInput.focus();

				// 3秒后恢复原样式
				setTimeout(function () {
					emailInput.style.borderColor = '';
				}, 3000);

				return;
			}

			// 这里可以添加 AJAX 提交逻辑或替换为实际的邮件服务 API
			// 目前仅做前端演示反馈
			alert('\u8c22\u8c22\u8ba2\u9605\uff01\u6211\u4eec\u4f1a\u53ca\u65f6\u5411\u60a8\u7684\u90ae\u7bb1\u53d1\u9001\u6700\u65b0\u5185\u5bb9\u3002');
			emailInput.value = '';
		});
	}


	// =====================================================
	// 6. 入场动画触发器（使用 IntersectionObserver）
	// =====================================================

	if ('IntersectionObserver' in window) {
		// 监视需要动画的元素
		var animatedElements = document.querySelectorAll('.post-card, .featured-post, .sidebar-widget');

		var observer = new IntersectionObserver(function (entries) {
			for (var k = 0; k < entries.length; k++) {
				if (entries[k].isIntersecting) {
					entries[k].target.style.opacity = '1';
					entries[k].target.style.transform = 'translateY(0)';
					observer.unobserve(entries[k].target); // 只触发一次
				}
			}
		}, {
			rootMargin: '0px 0px -50px 0px', // 略微提前触发
			threshold: 0.1
		});

		for (var m = 0; m < animatedElements.length; m++) {
			// 初始隐藏状态
			animatedElements[m].style.opacity = '0';
			animatedElements[m].style.transform = 'translateY(24px)';
			animatedElements[m].style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';

			observer.observe(animatedElements[m]);
		}
	}


	// =====================================================
	// 7. 图片加载错误处理
	// =====================================================

	var postImages = document.querySelectorAll('.post-card-image, .featured-post-image');

	for (var n = 0; n < postImages.length; n++) {
		postImages[n].addEventListener('error', function () {
			this.onerror = null; // 防止无限循环
			this.src = window.neoBrutalismData && window.neoBrutalismData.themePath
				? window.neoBrutalismData.themePath + '/assets/default-post.svg'
				: '';
			this.style.backgroundColor = 'var(--color-muted)';
			this.alt = '\u56fe\u7247\u52a0\u8f7d\u5931\u8d25';
		});
	}

})();
