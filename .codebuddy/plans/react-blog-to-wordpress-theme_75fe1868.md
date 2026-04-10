---
name: react-blog-to-wordpress-theme
overview: 将基于 React + Tailwind CSS v4 + shadcn/ui 的博客项目转换为可直接使用的 WordPress 主题，包含所有必需的模板文件（style.css, functions.php, index.php 等），保留原有样式和交互功能。
design:
  architecture:
    framework: html
  styleKeywords:
    - Neo-Brutalism
    - Bold Borders
    - Offset Shadows
    - Rose Pink Accent
    - Warm Neutral Background
    - Serif Headings
    - Glassmorphism Header
    - Micro-animations
    - Dark Mode
    - Mobile Responsive
  fontSystem:
    fontFamily: Poppins, Lora, Fira Code
    heading:
      size: 36px
      weight: 700
    subheading:
      size: 20px
      weight: 600
    body:
      size: 16px
      weight: 400
  colorSystem:
    primary:
      - "#C56B8A"
      - "#D4899E"
      - "#E8AABB"
    background:
      - "#F5F0EB"
      - "#FAF8F5"
      - "#FFFFFF"
    text:
      - "#3D322E"
      - "#6B5B54"
      - "#8A7A72"
    functional:
      - "#E85D75"
      - "#4A9D6E"
      - "#D4A843"
todos:
  - id: create-theme-skeleton
    content: 创建 WordPress 主题骨架：style.css（主题声明+完整CSS变量/重置/排版/动画/工具类）、functions.php（脚本入队/菜单/widget/特色图片支持）
    status: completed
  - id: create-header-footer
    content: 创建 header.php（粘性导航栏/Logo/菜单walker/主题切换/移动端汉堡菜单/SVG图标）和 footer.php（三栏布局/品牌/导航/联系/版权）
    status: completed
    dependencies:
      - create-theme-skeleton
  - id: create-index-main
    content: 创建 index.php 主模板（Hero标题/精选文章双栏卡片/PostCard网格/侧边栏集成/加载更多按钮），使用 [code-explorer] 确认组件DOM结构
    status: completed
    dependencies:
      - create-header-footer
  - id: create-template-pages
    content: 创建 single.php（文章详情/特色图/内容/评论）、page.php（自定义页面）、archive.php（归档网格/分页）、search.php（搜索结果）、404.php（错误页）、comments.php（评论模板）
    status: completed
    dependencies:
      - create-index-main
  - id: create-sidebar-js
    content: 创建 sidebar.php（搜索/分类/热门/标签云/订阅面板）和 js/theme.js（localStorage暗色切换/移动端导航切换逻辑）
    status: completed
    dependencies:
      - create-header-footer
---

## 产品概述

将当前基于 React + Vite + Tailwind CSS v4 的博客前端项目，完整转换为可直接在 WordPress 中安装使用的主题。主题文件将输出到"主题"文件夹中，严格遵循 WordPress 主题目录结构和编码标准。

## 核心功能

- **WordPress 主题骨架**: 生成 style.css（含主题声明头）、functions.php、index.php 等必需文件
- **完整模板体系**: header.php（粘性导航栏+Logo+菜单+主题切换+移动端汉堡菜单）、footer.php（三栏页脚）、sidebar.php（搜索/分类/热门/标签云/订阅）、single.php（单篇文章详情）、page.php（自定义页面）、archive.php（分类/标签归档）、search.php（搜索结果）、404.php（错误页面）
- **样式系统迁移**: 将 Tailwind CSS v4 的 oklch 色彩变量、brutalist 阴影效果、粗边框风格、暗色模式、动画效果全部转换为纯 CSS，保留原设计的视觉完整性
- **WordPress 数据对接**: 将 React mock 数据替换为 WordPress 循环（WP_Query / the_post 等），文章标题/摘要/分类/作者/日期/评论数/特色图片均使用 WordPress 模板标签动态输出
- **交互功能保留**: 移动端响应式导航切换、暗色/亮色主题切换（localStorage 持久化）、hover 动画效果、图片缩放过渡
- **字体与图标**: 引入 Poppins/Lora 字体，使用 SVG 内联图标替代 lucide-react

## 技术栈

- **目标平台**: WordPress 6.0+
- **模板语言**: PHP 7.4+ (WordPress 编码规范)
- **样式方案**: 纯 CSS（从 Tailwind CSS v4 迁移而来，不依赖任何 CSS 框架或构建工具）
- **JavaScript**: 原生 JS（用于主题切换和移动端菜单，无框架依赖）
- **图标**: SVG 内联（从 lucide-react 图标迁移）

## 实现方案

### 核心策略：CSS 静态化 + PHP 模板化

1. **Tailwind CSS → 纯 CSS 转换**: 将 `src/index.css` 中的所有 oklch 色值、自定义属性、工具类、动画关键帧原样转换为标准 CSS。Tailwind 的 utility 类按组件拆分为对应的语义化 CSS 类名。
2. **React 组件 → WordPress 模板**: 每个 React 组件函数映射为对应的 WordPress 模板文件或可复用的模板片段。`BlogHeader` → `header.php` + 导航 walker；`FeaturedPost`/`PostCard` → `index.php`/`archive.php`/`single.php` 中的循环体；`Sidebar` → `sidebar.php` + widget area 注册；`BlogFooter` → `footer.php`。
3. **Mock 数据 → WordPress Loop**: 使用 `have_posts()` / `the_post()` / WordPress 模板标签（`the_title()`, `the_excerpt()`, `the_category()`, `get_the_author_meta()`, `get_the_date()`, `comments_number()`, `the_post_thumbnail()` 等）替换硬编码数据。
4. **next-themes → 原生 JS 主题切换**: 用纯 JavaScript 实现 localStorage 读写 + body 类名切换，通过 `functions.php` 局部化内联脚本。

### 架构设计

- **模板层次结构**: 遵循 WordPress Template Hierarchy，index.php 作为 fallback，single.php 处理单篇，archive.php 处理归档
- **Widget Areas**: 在 functions.php 中注册 sidebar 区域，支持 WordPress Widget 系统
- **菜单系统**: 注册一个导航菜单位置 `primary-menu`
- **主题特性**: 支持 `post-thumbnails`（特色图片）, `custom-logo`, `html5`, `title-tag`

### 关键技术决策

- **不使用 Tailwind CDN 或构建步骤**: 直接生成编译后的静态 CSS，确保主题即装即用，无外部依赖
- **oklch 色值保留**: 现代浏览器均支持 oklch，无需转换为 hex/rgb
- **图标用 SVG 字符串内联**: 避免引入图标库依赖，保持主题自包含

## 目录结构

```
c:\Users\28928\Downloads\博客页面\主题\
├── style.css                  # [NEW] 主题声明头 + 全局 CSS 变量/重置/排版/工具类
├── functions.php              # [NEW] 主题初始化：脚本/样式入队、菜单注册、widget区域、特色图片支持
├── index.php                  # [NEW] 主模板：博客首页（精选文章 + 文章网格 + 侧边栏）
├── header.php                 # [NEW] 页头：<head> + 粘性导航栏（Logo/导航/主题切换/移动端菜单）
├── footer.php                 # [NEW] 页脚：三栏布局（品牌/导航/联系）+ 版权信息
├── sidebar.php                # [NEW] 侧边栏：搜索框/分类列表/热门文章/标签云/邮件订阅
├── single.php                 # [NEW] 单篇文章详情页模板
├── page.php                   # [NEW] 自定义页面模板
├── archive.php                # [NEW] 分类/标签/日期归档页模板
├── search.php                 # [NEW] 搜索结果页模板
├── 404.php                    # [NEW] 404 错误页模板
├── comments.php               # [NEW] 评论列表模板
├── js/
│   └── theme.js               # [NEW] 主题交互脚本：暗色切换 + 移动端导航切换
└── screenshot.png             # [NEW] 主题预览图占位说明（可选）
```

## 关键实现细节

- **style.css 主题声明头** 必须以 `/* Theme Name: ... */` 开头，包含主题名称、作者、版本、描述等信息
- **色彩系统完整映射**: 将 index.css 中 @theme inline 定义的 ~40 个 oklch 色值和 dark 模式覆盖值全部转为 :root / .dark CSS 自定义属性
- **brutal-shadow 效果**: `box-shadow: 4px 4px 0px 0px <primary-color>` 保持粗边框 brutalist 设计风格
- **动画关键帧**: fade-in-up / slide-in-right / scale-in / accordion-down/up 原样保留
- **响应式断点**: 保持 md(768px)/lg(1024px) 断点逻辑，移动端优先
- **WordPress 安全**: 所有输出使用 `esc_attr()` / `esc_html()` / `wp_kses()` 进行转义

## 设计风格：Brutalist Neo 博客

采用新粗野主义（Neo-Brutalism）设计风格，结合现代博客布局，打造具有强烈视觉识别度的技术博客主题。

### 页面规划（5个核心页面）

#### 1. 首页 (index.php) - 博客主列表页

- **顶部导航区块**: 固定在顶部的半透明毛玻璃导航栏，包含 Logo（B 图标 + Blog. 文字）、水平导航链接（首页/文章/分类/关于）、亮暗主题切换按钮、订阅按钮、移动端汉堡菜单按钮
- **Hero 标题区块**: 页面大标题"技术笔记"配合副标题描述文字，带 fade-in-up 入场动画
- **精选文章区块**: 大型双栏卡片布局，左侧文章封面图（悬停放大），右侧包含分类标签、发布日期阅读时间、文章标题、摘要、作者头像姓名、"阅读全文"链接，整体使用粗边框 + brutalist 阴影
- **最新文章网格区块**: 2列响应式网格，每张卡片包含封面图（悬停放大+分类角标）、日期/阅读时间、标题（悬停变色）、3行截断摘要、底部作者+评论数分割线
- **加载更多按钮**: 居中的轮廓按钮，悬停时阴影偏移增强
- **侧边栏区块**: 固定宽度320px，包含5个面板（搜索框/分类带计数/热门文章排行/标签云/邮件订阅），每个面板使用粗边框圆角卡片

#### 2. 单篇文章页 (single.php)

- **文章头部**: 特色图片大图展示、分类标签、标题、作者信息行（头像+姓名+日期+阅读时间）
- **正文内容区**: 排版优雅的文章内容区域，合适的行高和段落间距
- **评论区**: 文章下方的评论列表和评论表单
- **文章导航**: 上一篇/下一篇文章导航

#### 3. 归档页 (archive.php)

- **归档标题**: 显示"分类: XXX"或"标签: XXX"
- **文章列表**: 与首页相同卡片样式的文章网格
- **分页导航**: 数字分页或上一页/下一页

#### 4. 搜索结果页 (search.php)

- **搜索框**: 可重复使用的搜索输入框
- **结果统计**: "搜索 XXX 共找到 N 篇文章"
- **结果列表**: 与首页一致的文章卡片网格

#### 5. 404错误页 (404.php)

- **居中提示**: "404 - 页面未找到"大标题
- **返回首页按钮**: 引导用户返回的 CTA 按钮

### 全局设计特征

- **色彩**: 以玫瑰粉红色系（oklch primary）为主色调，暖米色背景，深色模式下深蓝灰背景配柔和亮色前景
- **边框**: 统一 2px 粗实线边框，营造大胆的几何感
- **阴影**: brutalist 偏移阴影（非模糊），4px/6px 偏移量，悬停时增大偏移并反向位移
- **字体**: 标题使用 Lora 衬线字体，正文使用 Poppins 无衬线字体
- **动效**: fadeInUp 入场动画、图片 hover 缩放、按钮 hover 阴影位移、导航链接下划线滑入

## Agent Extensions

### SubAgent

- **code-explorer**
- Purpose: 深度探索项目中 components/ui 目录下的 shadcn/ui 组件源码，确认 Button、Badge、Avatar、Input、Separator 等组件的具体 HTML 结构和 CSS 类名，确保转换后的 WordPress 模板能精确还原组件样式
- Expected outcome: 获得每个 UI 组件的 DOM 结构细节和完整的类名列表，用于生成准确的 PHP/HTML 输出