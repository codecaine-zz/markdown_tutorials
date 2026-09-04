<?php
// Configure error handling early
ini_set('log_errors', '1');
if (!is_dir(__DIR__ . '/logs')) {
    @mkdir(__DIR__ . '/logs', 0777, true);
}
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
if (!isset($_ENV['SHOW_PHP_WARNINGS'])) {
    ini_set('display_errors', '0');
}
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);

// Error handling configured above

class MarkdownTutorialApp {
    private $baseDir;
    
    
    public function __construct() {
        $this->baseDir = __DIR__;
    }
    
    public function run() {
        $page = $_GET['page'] ?? 'home';
    return $this->renderPage($page);
    }
    
    // Build an absolute URL that respects subdirectory hosting and optionally appends a cache-busting version.
    private function assetUrl($relativePath, $versioned = false) {
        $urlPath = ltrim($relativePath, '/');
        $prefix = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        if ($prefix === '/' || $prefix === '\\' || $prefix === '.' || $prefix === '') {
            $prefix = '';
        }
        $url = ($prefix ? $prefix . '/' : '/') . $urlPath;
        if ($versioned) {
            $file = $this->baseDir . '/' . $urlPath;
            $v = @filemtime($file) ?: time();
            $url .= '?v=' . $v;
        }
        return $url;
    }
    
    private function renderPage($page) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dev Knowledge Base &amp; Tutorials | Programming, Client Utils &amp; CLI</title>
            <meta name="description" content="Comprehensive full-stack programming and developer knowledge base with 400+ guides: complete Python standard library (300+ modules) &amp; web frameworks, modern JavaScript &amp; Bun runtime, client utilities (Axios, es-toolkit), CLI tooling (Chalk, Boxen, Clack Prompts, CLI-Table3), systems programming in Rust and C on ARM Mac, databases, and DevOps.">
            <meta name="keywords" content="programming tutorials, python standard library, client utils, cli tools, bun runtime, typescript, chalk, boxen, clack prompts, axios, es-toolkit, rust, c arm mac, fastapi, django, dev cheat sheets">
            <!-- Site favicon (only include if files exist to avoid 404s) -->
            <?php 
            $faviconFiles = [
                'favicon.ico' => 'sizes="any"',
                'favicon.svg' => 'type="image/svg+xml"',
                'favicon-32x32.png' => 'type="image/png" sizes="32x32"',
                'favicon-16x16.png' => 'type="image/png" sizes="16x16"',
                'apple-touch-icon.png' => 'rel="apple-touch-icon" sizes="180x180"'
            ];
            
            foreach ($faviconFiles as $file => $attrs) {
                if (file_exists($this->baseDir . '/' . $file)) {
                    $rel = strpos($file, 'apple-touch') !== false ? 'apple-touch-icon' : 'icon';
                    if (strpos($file, 'apple-touch') === false && $file === 'favicon.ico') {
                        echo '<link rel="shortcut icon" href="' . htmlspecialchars($this->assetUrl($file, true)) . '">' . "\n            ";
                    }
                    echo '<link rel="' . $rel . '" href="' . htmlspecialchars($this->assetUrl($file, true)) . '" ' . $attrs . '>' . "\n            ";
                }
            }
            ?>
            <meta name="theme-color" content="#0f172a">
            <!-- Highlight.js with comprehensive language support -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
            <link rel="stylesheet" href="assets/css/style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <script defer src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
            <!-- Highlight.js common bundle (includes popular languages in a single file) -->
            <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
            <!-- Highlight.js V language support (local vendored fallback) -->
            <script defer src="assets/js/highlight-languages/v.js"></script>
        </head>
        <body>
            <!-- Scroll Progress Indicator -->
            <div id="scrollProgress" class="scroll-progress" aria-hidden="true"></div>

            <!-- Mobile Menu Backdrop -->
            <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="toggleSidebar(false)" aria-hidden="true"></div>

            <div class="container">
                <!-- Sidebar Navigation -->
                <nav class="sidebar" id="sidebar" aria-label="Documentation Navigation">
                    <div class="sidebar-header">
                        <div class="sidebar-top-bar">
                            <a href="/" class="sidebar-hub-btn" title="Return to CodeCaine Hub">
                                <i class="fas fa-arrow-left"></i>
                                <span>CodeCaine Hub</span>
                            </a>
                            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar Width (Desktop)" aria-label="Toggle Sidebar Width">
                                <i class="fas fa-arrows-alt-h"></i>
                            </button>
                        </div>
                        <div class="sidebar-brand-row">
                            <div class="sidebar-brand-icon">
                                <i class="fas fa-book-bookmark"></i>
                            </div>
                            <div class="sidebar-brand-info">
                                <span class="brand-title">Tutorials &amp; Guides</span>
                                <span class="brand-badge"><span class="badge-dot"></span> 400+ Guides</span>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-content">
                            <!-- Sidebar search -->
                            <div class="search-container">
                                <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="navigationSearch" placeholder="Search tutorials... (⌘K)" autocomplete="off" />
                                    <button class="clear-search" id="clearSearch" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Navigation tree -->
                            <div id="nav-items">
                                <?php echo $this->generateNavigation(); ?>
                            </div>
                    </div>
                </nav>
                
                <!-- Main Content -->
                <main class="main-content">
                    <!-- Top Toolbar -->
                    <header class="toolbar" role="toolbar" aria-label="Application toolbar">
                        <div class="toolbar-left">
                            <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleSidebar()" aria-label="Toggle Navigation Menu" title="Toggle Navigation Menu">
                                <i class="fas fa-bars"></i>
                            </button>
                            <a href="/" class="tb-hub-pill" title="Return to CodeCaine Hub">
                                <i class="fas fa-house"></i>
                                <span>Hub</span>
                            </a>
                            <div class="tb-sep" aria-hidden="true"></div>
                            <button id="tbHome" class="tb-btn" title="Home"><i class="fas fa-home"></i><span>Home</span></button>
                            <button id="tbCollapseAll" class="tb-btn" title="Collapse all folders"><i class="fas fa-compress"></i><span>Collapse</span></button>
                            <button id="tbExpandAll" class="tb-btn" title="Expand all folders"><i class="fas fa-expand"></i><span>Expand</span></button>
                            <button id="tbRefresh" class="tb-btn" title="Refresh navigation"><i class="fas fa-rotate"></i><span>Refresh</span></button>
                        </div>
                        <div class="toolbar-center">
                            <button class="tb-search-trigger" id="tbSearchTrigger" onclick="window.showCommandPalette &amp;&amp; window.showCommandPalette()" title="Open Command Palette (⌘K / Ctrl+K)">
                                <i class="fas fa-search"></i>
                                <span>Quick search...</span>
                                <kbd>⌘K</kbd>
                            </button>
                        </div>
                        <div class="toolbar-right">
                            <button id="tbHelp" class="tb-btn" title="Keyboard shortcuts (F1)"><i class="fas fa-question-circle"></i><span>Help</span></button>
                            <button id="tbTheme" class="tb-btn" title="Toggle theme"><i class="fas fa-moon"></i><span>Theme</span></button>
                            <div class="tb-sep" aria-hidden="true"></div>
                            <div class="zoom-controls">
                                <button id="tbZoomOut" class="tb-btn tb-btn-compact" title="Zoom out"><i class="fas fa-search-minus"></i></button>
                                <span id="tbZoomIndicator" class="tb-indicator" aria-live="polite">100%</span>
                                <button id="tbZoomIn" class="tb-btn tb-btn-compact" title="Zoom in"><i class="fas fa-search-plus"></i></button>
                            </div>
                        </div>
                    </header>

                    <!-- Zoom container wraps the dynamic content -->
                    <div id="contentZoom" class="zoom-container">
                        <?php echo $this->renderContent($page); ?>
                    </div>
                </main>
            </div>
            
            <!-- Busy spinner overlay -->
            <div id="busyOverlay" class="busy-overlay" aria-hidden="true">
                <div class="spinner" role="status" aria-label="Loading">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>

            <?php $appJsV = @filemtime(__DIR__ . '/assets/js/app.js') ?: time(); ?>
            <script src="assets/js/app.js?v=<?php echo $appJsV; ?>"></script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    private function generateNavigation() {
        $navigation = '';
        $tutorialsDir = $this->baseDir . '/tutorials';
        $currentPage = $_GET['page'] ?? '';
        
        if (is_dir($tutorialsDir)) {
            $navigation .= $this->buildNavigationTree($tutorialsDir, '', $currentPage);
        }
        
        return $navigation;
    }
    
    private function buildNavigationTree($dir, $prefix = '', $currentPage = '') {
        $items = [];
        $files = [];
        
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] === '.') continue;
                
                $fullPath = $dir . '/' . $entry;
                if (is_dir($fullPath)) {
                    $items[] = [
                        'type' => 'folder',
                        'name' => $entry,
                        'path' => $fullPath,
                        'url_name' => $prefix . $entry
                    ];
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) === 'md') {
                    $files[] = [
                        'type' => 'file',
                        'name' => pathinfo($entry, PATHINFO_FILENAME),
                        'path' => $fullPath,
                        'url_name' => $prefix . pathinfo($entry, PATHINFO_FILENAME)
                    ];
                }
            }
            closedir($handle);
        }
        
        // Sort items
        usort($items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        usort($files, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        $html = '';
        
        // Render folders first
        foreach ($items as $item) {
            $folderName = ucwords(str_replace(['-', '_'], ' ', $item['name']));
            $folderId = 'folder-' . md5($item['url_name']);
            
            // Check if this folder should be expanded (if current page is inside this folder)
            $isExpanded = $currentPage && strpos($currentPage, $item['url_name']) === 0;
            $expandedClass = $isExpanded ? ' open' : '';
            $expandedStyle = $isExpanded ? ' style="max-height: none;"' : '';
            $arrowClass = $isExpanded ? ' open' : '';
            
            $html .= '<div class="nav-folder">';
            $html .= '<div class="nav-folder-header">';
            $html .= '<i class="fas fa-folder folder-icon"></i>';
            $html .= '<span>' . htmlspecialchars($folderName) . '</span>';
            $html .= '<i class="fas fa-chevron-right folder-arrow' . $arrowClass . '"></i>';
            $html .= '</div>';
            $html .= '<div class="nav-folder-content' . $expandedClass . '" id="' . $folderId . '"' . $expandedStyle . '>';
            $html .= $this->buildNavigationTree($item['path'], $item['url_name'] . '/', $currentPage);
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Then render files
        foreach ($files as $file) {
            $fileName = ucwords(str_replace(['-', '_'], ' ', $file['name']));
            $isActive = $currentPage === $file['url_name'];
            $activeClass = $isActive ? ' class="active"' : '';
            
            $html .= '<div class="nav-item">';
            $href = '?page=' . rawurlencode($file['url_name']);
            $html .= '<a href="' . $href . '"' . $activeClass . '>';
            $html .= '<i class="fas fa-file-alt"></i>';
            $html .= '<span>' . htmlspecialchars($fileName) . '</span>';
            $html .= '</a>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    private function renderContent($page) {
        if ($page === 'home') {
            return $this->renderHomePage();
        }

        // First try to find a markdown file
        $filePath = $this->findMarkdownFile($page);
        if ($filePath && file_exists($filePath)) {
            // We found a markdown file - render it
            $relative = str_replace($this->baseDir . DIRECTORY_SEPARATOR, '', $filePath);
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);

            $output = '<div class="content-wrapper">';
            $output .= $this->generateBreadcrumb($page);
            
            // Add navigation between tutorials
            $output .= $this->generateTutorialNavigation($page);

                // Controls above content (Table of Contents toggle)
                $output .= '<div class="tutorial-actions" style="display:flex;gap:10px;margin:10px 0;flex-wrap:wrap;align-items:center;">';
                $output .= '<button class="btn-secondary" id="tocToggle" onclick="toggleTableOfContents()" title="Hide table of contents">';
                $output .= '<i class="fas fa-list"></i> Contents';
                $output .= '</button>';
                
                $output .= '</div>';

                // Content + ToC layout wrapper
                $output .= '<div class="article-layout">';
                    // Table of Contents container (visible by default)
                    $output .= '<div class="table-of-contents" id="tableOfContents" style="display:block;">';
                    $output .= '<h3 class="toc-title"><i class="fas fa-list"></i> Table of Contents</h3>';
                    $output .= '<ul class="toc-list"></ul>';
                    $output .= '</div>';

                    // Client-side render placeholder
                    $output .= '<div class="markdown-content" data-md-src="' . htmlspecialchars($relative) . '">';
                    $output .= '<div class="loading">Loading tutorial…</div>';
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
            return $output;
        } else {
            // No markdown file found - check if this is a valid folder path
            $folderPath = $this->getFolderPath($page);
            if ($folderPath && is_dir($folderPath)) {
                // This is a folder - render a folder listing page
                return $this->renderFolderListing($page, $folderPath);
            } else {
                // Neither file nor folder found - show 404
                return $this->render404();
            }
        }
    }
    
    private function findMarkdownFile($page) {
        $page = preg_replace('/\.md$/i', '', $page);
        $parts = explode('/', $page);
        $fileName = array_pop($parts) . '.md';
        $subPath = implode('/', $parts);
        
        $searchPath = $this->baseDir . '/tutorials';
        if (!empty($subPath)) {
            $searchPath .= '/' . $subPath;
        }
        
        $fullPath = $searchPath . '/' . $fileName;
        
        return file_exists($fullPath) ? $fullPath : null;
    }
    
    private function getFolderPath($page) {
        $parts = explode('/', $page);
        $subPath = implode('/', $parts);
        
        $searchPath = $this->baseDir . '/tutorials';
        if (!empty($subPath)) {
            $searchPath .= '/' . $subPath;
        }
        
        return $searchPath;
    }
    
    private function renderFolderListing($page, $folderPath) {
        $output = '<div class="content-wrapper">';
        $output .= $this->generateBreadcrumb($page);
        
        $output .= '<div class="folder-listing">';
        $output .= '<div class="folder-header">';
        $output .= '<h1><i class="fas fa-folder-open"></i> ' . ucwords(str_replace(['/', '-', '_'], [' / ', ' ', ' '], $page)) . '</h1>';
        $output .= '<p class="folder-description">Browse the contents of this section:</p>';
        $output .= '</div>';
        
        // Generate the folder content listing
        $output .= $this->generateFolderContents($folderPath, $page);
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    private function generateFolderContents($folderPath, $currentPage) {
        $items = [];
        $files = [];
        
        if ($handle = opendir($folderPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] === '.') continue;
                
                $fullPath = $folderPath . '/' . $entry;
                if (is_dir($fullPath)) {
                    $items[] = [
                        'type' => 'folder',
                        'name' => $entry,
                        'path' => $fullPath,
                        'url_name' => $currentPage . '/' . $entry
                    ];
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) === 'md') {
                    $files[] = [
                        'type' => 'file',
                        'name' => pathinfo($entry, PATHINFO_FILENAME),
                        'path' => $fullPath,
                        'url_name' => $currentPage . '/' . pathinfo($entry, PATHINFO_FILENAME)
                    ];
                }
            }
            closedir($handle);
        }
        
        // Sort items
        usort($items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        usort($files, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        $output = '<div class="folder-contents">';
        
        // Render folders first
        if (!empty($items)) {
            $output .= '<div class="content-section">';
            $output .= '<h2><i class="fas fa-folder"></i> Folders</h2>';
            $output .= '<div class="folder-grid">';
            foreach ($items as $item) {
                $folderName = ucwords(str_replace(['-', '_'], ' ', $item['name']));
                $output .= '<div class="folder-card">';
                $output .= '<a href="?page=' . rawurlencode($item['url_name']) . '">';
                $output .= '<i class="fas fa-folder-open"></i>';
                $output .= '<span>' . htmlspecialchars($folderName) . '</span>';
                $output .= '<i class="fas fa-chevron-right"></i>';
                $output .= '</a>';
                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '</div>';
        }
        
        // Render files
        if (!empty($files)) {
            $output .= '<div class="content-section">';
            $output .= '<h2><i class="fas fa-file-alt"></i> Tutorials</h2>';
            $output .= '<div class="file-grid">';
            foreach ($files as $file) {
                $fileName = ucwords(str_replace(['-', '_'], ' ', $file['name']));
                $output .= '<div class="file-card">';
                $output .= '<a href="?page=' . rawurlencode($file['url_name']) . '">';
                $output .= '<i class="fas fa-file-alt"></i>';
                $output .= '<span>' . htmlspecialchars($fileName) . '</span>';
                $output .= '<i class="fas fa-arrow-right"></i>';
                $output .= '</a>';
                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '</div>';
        }
        
        if (empty($items) && empty($files)) {
            $output .= '<div class="empty-folder">';
            $output .= '<i class="fas fa-folder-open"></i>';
            $output .= '<p>This folder is empty.</p>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    private function generateBreadcrumb($page) {
        $parts = explode('/', $page);
        $breadcrumb = '<nav class="breadcrumb">';
        $breadcrumb .= '<div class="breadcrumb-nav">';
        $breadcrumb .= '<button class="nav-btn" id="back-btn" onclick="goBack()" title="Go Back">';
        $breadcrumb .= '<i class="fas fa-arrow-left"></i>';
        $breadcrumb .= '</button>';
        $breadcrumb .= '<button class="nav-btn" id="forward-btn" onclick="goForward()" title="Go Forward">';
        $breadcrumb .= '<i class="fas fa-arrow-right"></i>';
        $breadcrumb .= '</button>';
        $breadcrumb .= '</div>';
        $breadcrumb .= '<div class="breadcrumb-path">';
        $breadcrumb .= '<a href="?page=home"><i class="fas fa-home"></i> Home</a>';
        
        $currentPath = '';
        for ($i = 0; $i < count($parts); $i++) {
            $part = $parts[$i];
            
            if (!empty($currentPath)) {
                $currentPath .= '/';
            }
            $currentPath .= $part;
            
            $displayName = ucwords(str_replace(['-', '_'], ' ', $part));
            $breadcrumb .= ' <i class="fas fa-chevron-right"></i> ';
            
            // Make all parts except the last one clickable
            if ($i < count($parts) - 1) {
                // This is an intermediate path segment - make it clickable
                $breadcrumb .= '<a href="?page=' . rawurlencode($currentPath) . '" class="breadcrumb-link">';
                $breadcrumb .= htmlspecialchars($displayName);
                $breadcrumb .= '</a>';
            } else {
                // This is the current page - show as non-clickable text
                $breadcrumb .= '<span class="breadcrumb-current">' . htmlspecialchars($displayName) . '</span>';
            }
        }
        
        $breadcrumb .= '</div>';
        $breadcrumb .= '</nav>';
        return $breadcrumb;
    }
    
    private function generateTutorialNavigation($page) {
        $allTutorials = $this->getAllTutorials();
        $currentIndex = array_search($page, array_column($allTutorials, 'page'));
        
        if ($currentIndex === false) {
            return '';
        }
        
        $navigation = '<div class="tutorial-navigation">';
        
        // Previous tutorial
        if ($currentIndex > 0) {
            $prev = $allTutorials[$currentIndex - 1];
            $navigation .= '<a href="?page=' . rawurlencode($prev['page']) . '" class="nav-tutorial nav-tutorial-prev">';
            $navigation .= '<i class="fas fa-chevron-left"></i>';
            $navigation .= '<div class="nav-tutorial-content">';
            $navigation .= '<span class="nav-tutorial-label">Previous</span>';
            $navigation .= '<span class="nav-tutorial-title">' . htmlspecialchars($prev['title']) . '</span>';
            $navigation .= '</div>';
            $navigation .= '</a>';
        } else {
            $navigation .= '<div class="nav-tutorial nav-tutorial-disabled"></div>';
        }
        
        // Next tutorial
        if ($currentIndex < count($allTutorials) - 1) {
            $next = $allTutorials[$currentIndex + 1];
            $navigation .= '<a href="?page=' . rawurlencode($next['page']) . '" class="nav-tutorial nav-tutorial-next">';
            $navigation .= '<div class="nav-tutorial-content">';
            $navigation .= '<span class="nav-tutorial-label">Next</span>';
            $navigation .= '<span class="nav-tutorial-title">' . htmlspecialchars($next['title']) . '</span>';
            $navigation .= '</div>';
            $navigation .= '<i class="fas fa-chevron-right"></i>';
            $navigation .= '</a>';
        } else {
            $navigation .= '<div class="nav-tutorial nav-tutorial-disabled"></div>';
        }
        
        $navigation .= '</div>';
        return $navigation;
    }
    
    private function getAllTutorials() {
        $tutorials = [];
        $this->collectTutorials($this->baseDir . '/tutorials', '', $tutorials);
        return $tutorials;
    }
    
    private function collectTutorials($dir, $prefix, &$tutorials) {
        if (!is_dir($dir)) return;
        
        $items = [];
        $files = [];
        
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] === '.') continue;
                
                $fullPath = $dir . '/' . $entry;
                if (is_dir($fullPath)) {
                    $items[] = [
                        'type' => 'folder',
                        'name' => $entry,
                        'path' => $fullPath,
                        'url_name' => $prefix . $entry
                    ];
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) === 'md') {
                    $files[] = [
                        'type' => 'file',
                        'name' => pathinfo($entry, PATHINFO_FILENAME),
                        'path' => $fullPath,
                        'url_name' => $prefix . pathinfo($entry, PATHINFO_FILENAME)
                    ];
                }
            }
            closedir($handle);
        }
        
        // Sort items
        usort($items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        usort($files, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        // Add files from current directory first
        foreach ($files as $file) {
            $title = ucwords(str_replace(['-', '_'], ' ', $file['name']));
            $tutorials[] = [
                'page' => $file['url_name'],
                'title' => $title,
                'path' => $file['path']
            ];
        }
        
        // Then recurse into folders
        foreach ($items as $item) {
            $this->collectTutorials($item['path'], $item['url_name'] . '/', $tutorials);
        }
    }
    
    // Copy buttons are added client-side after rendering
    
    private function renderHomePage() {
        // Stats and dynamic sections
        $stats = $this->getTutorialStats();
        $categories = $this->getTopCategories();
        $recent = $this->getRecentTutorials(6);
        $random = $this->getRandomTutorial();

        ob_start();
        ?>
        <div class="content-wrapper">
            <div class="home-page">
                <div class="hero-badge"><i class="fas fa-layer-group"></i> 400+ Production Cheat-Sheets &amp; Developer Guides</div>
                <h1><i class="fas fa-terminal"></i> Developer Knowledge Base &amp; Engineering Hub</h1>
                <p class="hero-lead">An encyclopedic reference library of 400+ practical, field-tested developer guides. Dive deep into the complete Python Standard Library (300+ modules), modern JavaScript/TypeScript with Bun runtime &amp; Drizzle ORM, client utilities &amp; CLI tools (Chalk, Boxen, Clack Prompts, Axios, es-toolkit), systems programming in Rust and C on ARM Apple Silicon, and 100+ command-line tools.</p>

                <!-- Quick actions -->
                <div class="tutorial-actions" style="display:flex;flex-wrap:wrap;gap:10px;margin:18px 0 24px 0;">
                    <button class="btn" onclick="(function(){const s=document.getElementById('navigationSearch'); if(s){s.focus(); s.select();}})()" title="Focus the sidebar search">
                        <i class="fas fa-search"></i> Search all 400+ guides
                    </button>
                    <button class="btn-secondary" onclick="(function(){if(window.MarkdownApp &amp;&amp; window.MarkdownApp.openCommandPalette){window.MarkdownApp.openCommandPalette();}else{const ev=new KeyboardEvent('keydown',{key:'k',metaKey:true,bubbles:true});document.dispatchEvent(ev);}})()" title="Open Command Palette (Cmd+K)">
                        <i class="fas fa-bolt"></i> Command Palette <kbd style="font-size:0.75em;padding:2px 5px;background:rgba(255,255,255,0.15);border-radius:4px;margin-left:4px;">⌘K</kbd>
                    </button>
                    <?php if (!empty($random)) { ?>
                    <a class="btn-secondary" href="?page=<?php echo rawurlencode($random['page']); ?>" title="Open a random tutorial">
                        <i class="fas fa-shuffle"></i> Random guide
                    </a>
                    <?php } ?>
                </div>

                <!-- Core Engineering Domains Spotlight -->
                <div class="content-section" style="margin-top:20px;">
                    <h2><i class="fas fa-cubes"></i> Core Engineering Domains &amp; What's Inside</h2>
                    <p style="opacity:0.85; margin-bottom:16px;">Specialized learning tracks curated with runnable code snippets, architecture breakdowns, and syntax cheat-sheets:</p>
                    <div class="domains-grid">
                        <!-- Domain 1: Python Standard Library & Frameworks -->
                        <div class="domain-card">
                            <div class="domain-header">
                                <div class="domain-icon python-icon"><i class="fab fa-python"></i></div>
                                <div>
                                    <h3>Python Standard Library &amp; Web Frameworks</h3>
                                    <span class="domain-stat">256 Comprehensive Guides</span>
                                </div>
                            </div>
                            <p class="domain-desc">Deep-dive encyclopedic coverage of over 300+ Python standard library modules grouped by domain: Data Persistence (sqlite3, shelve, pickle), Functional (itertools, functools), Debugging (pdb, trace, timeit), File Formats (csv, tomllib, configparser), Tkinter GUI, Concurrency (asyncio, multiprocessing, threading), plus modern web frameworks (FastAPI, Django, Flask).</p>
                            <div class="domain-tags">
                                <span class="tag">Python 3.12+</span>
                                <span class="tag">StdLib (300+ Modules)</span>
                                <span class="tag">AsyncIO</span>
                                <span class="tag">FastAPI</span>
                                <span class="tag">SQLite3</span>
                                <span class="tag">Tkinter GUI</span>
                            </div>
                            <div class="domain-footer">
                                <a href="?page=programming%2Fpython" class="domain-link">Explore Python Guides <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Domain 2: Modern JavaScript, TypeScript & Bun Runtime -->
                        <div class="domain-card">
                            <div class="domain-header">
                                <div class="domain-icon js-icon"><i class="fab fa-js-square"></i></div>
                                <div>
                                    <h3>Modern JS, TypeScript &amp; Bun Runtime</h3>
                                    <span class="domain-stat">24 High-Performance Guides</span>
                                </div>
                            </div>
                            <p class="domain-desc">Cutting-edge JavaScript &amp; TypeScript development with the ultra-fast Bun runtime. Complete full-stack guides for Bun + Express stacks (integrated with SQLite, MySQL, and Redis), lightweight cross-platform desktop apps with Neutralinojs &amp; WebUI, type-safe database schemas with Drizzle ORM, and modern frontend guides.</p>
                            <div class="domain-tags">
                                <span class="tag">Bun Runtime</span>
                                <span class="tag">TypeScript</span>
                                <span class="tag">Drizzle ORM</span>
                                <span class="tag">Neutralinojs Desktop</span>
                                <span class="tag">WebUI Native GUI</span>
                                <span class="tag">Express Stacks</span>
                            </div>
                            <div class="domain-footer">
                                <a href="?page=programming%2Fjavascript" class="domain-link">Explore JS &amp; Bun Guides <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Domain 3: Client Utilities & CLI Developer Packages -->
                        <div class="domain-card">
                            <div class="domain-header">
                                <div class="domain-icon cli-icon"><i class="fas fa-terminal"></i></div>
                                <div>
                                    <h3>Client Utilities &amp; Interactive CLI Tools</h3>
                                    <span class="domain-stat">Production Packages &amp; Utilities</span>
                                </div>
                            </div>
                            <p class="domain-desc">Essential libraries for building delightful user experiences and command-line interfaces. Master modern HTTP data fetching with Axios HTTP Client, high-performance array/object helpers with es-toolkit, elegant modals with SweetAlert2, and CLI packages: Chalk terminal colors, Boxen framed banners, Clack interactive prompts, CLI-Table3 unicode tables, Nanospinner, and Consola.</p>
                            <div class="domain-tags">
                                <span class="tag">Axios HTTP Client</span>
                                <span class="tag">es-toolkit</span>
                                <span class="tag">Chalk Colors</span>
                                <span class="tag">Boxen Banners</span>
                                <span class="tag">Clack Prompts</span>
                                <span class="tag">CLI-Table3</span>
                                <span class="tag">SweetAlert2</span>
                            </div>
                            <div class="domain-footer">
                                <a href="?page=programming%2Fjavascript%2Fpackages" class="domain-link">Explore Client Utils &amp; CLI <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Domain 4: Systems Programming, DevOps & CLI Tools -->
                        <div class="domain-card">
                            <div class="domain-header">
                                <div class="domain-icon rust-icon"><i class="fas fa-server"></i></div>
                                <div>
                                    <h3>Systems, DevOps &amp; 100+ Command-Line Tools</h3>
                                    <span class="domain-stat">108 Guides across OS &amp; CLI</span>
                                </div>
                            </div>
                            <p class="domain-desc">Low-level systems programming and terminal mastery: Rust comprehensive guide, C on ARM Apple Silicon Mac, PHP language reference, and macOS Automator workflows. Plus cheat-sheets for 100+ CLI tools: Docker containerization with Bun installers, database clients (DuckDB, PostgreSQL, Redis, MySQL), multimedia processing (FFmpeg), text streams (awk, sed, jq), and network diagnostics.</p>
                            <div class="domain-tags">
                                <span class="tag">Rust</span>
                                <span class="tag">C on ARM Mac</span>
                                <span class="tag">Docker &amp; Bun</span>
                                <span class="tag">DuckDB &amp; SQL</span>
                                <span class="tag">FFmpeg</span>
                                <span class="tag">macOS Automator</span>
                            </div>
                            <div class="domain-footer">
                                <a href="?page=cli-tools" class="domain-link">Explore Systems &amp; CLI Tools <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="features" style="margin-top:20px;">
                    <div class="feature">
                        <i class="fas fa-file-code"></i>
                        <h3><?php echo (int)$stats['totalTutorials']; ?> Deep-Dive Guides</h3>
                        <p>Practical cheat-sheets with runnable code examples.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-folder-tree"></i>
                        <h3><?php echo (int)$stats['totalCategories']; ?> Curated Domains</h3>
                        <p>Programming, CLI tools, systems &amp; mathematics.</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-keyboard"></i>
                        <h3>Keyboard Superpowers</h3>
                        <p>Cmd+K palette, live search, and shortcuts modal.</p>
                    </div>
                </div>

                <!-- Interactive Productivity Superpowers -->
                <div class="content-section superpowers-card" style="margin-top:24px;">
                    <h2><i class="fas fa-wand-magic-sparkles"></i> Built for Developer Productivity</h2>
                    <div class="superpowers-grid">
                        <div class="superpower-item">
                            <i class="fas fa-bolt"></i>
                            <div>
                                <h4>Global Command Palette (<kbd>⌘K</kbd> / <kbd>Ctrl+K</kbd>)</h4>
                                <p>Instant keyboard-driven search to jump directly to any tutorial, switch themes, or toggle outlines without touching your mouse.</p>
                            </div>
                        </div>
                        <div class="superpower-item">
                            <i class="fas fa-code"></i>
                            <div>
                                <h4>Interactive Code Blocks with Line Numbers</h4>
                                <p>Syntax highlighted snippets with language tags, vertical line numbering, and a 1-click Copy button.</p>
                            </div>
                        </div>
                        <div class="superpower-item">
                            <i class="fas fa-list-check"></i>
                            <div>
                                <h4>Outline Table of Contents &amp; Anchor Links</h4>
                                <p>Sticky and floating right-rail TOC with live filter search, scrollspy active heading tracking, and deep-link copying.</p>
                            </div>
                        </div>
                        <div class="superpower-item">
                            <i class="fas fa-bookmark"></i>
                            <div>
                                <h4>Reading Progress &amp; Local Bookmarks</h4>
                                <p>100% client-side privacy: tracks your scroll percentage and bookmarked guides in localStorage with zero server tracking.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Browse by category -->
                <div class="content-section" style="margin-top:24px;">
                    <h2><i class="fas fa-sitemap"></i> Browse Directory Structure</h2>
                    <div class="folder-grid">
                        <?php if (empty($categories)) { ?>
                            <div class="empty-folder"><i class="fas fa-inbox"></i><p>No categories found.</p></div>
                        <?php } else { foreach ($categories as $cat) { ?>
                            <div class="folder-card">
                                <a href="?page=<?php echo rawurlencode($cat['page']); ?>">
                                    <i class="fas fa-folder-open"></i>
                                    <span><?php echo htmlspecialchars($cat['title']); ?></span>
                                    <span style="opacity:.75;font-size:.9em;">(<?php echo (int)$cat['count']; ?> guides)</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        <?php }} ?>
                    </div>
                </div>

                <!-- Recently added -->
                <div class="content-section" style="margin-top:24px;">
                    <h2><i class="fas fa-sparkles"></i> Recently Added &amp; Updated</h2>
                    <div class="file-grid" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr));">
                        <?php if (empty($recent)) { ?>
                            <div class="file-card"><div style="padding:16px;opacity:.8;">No recent tutorials yet.</div></div>
                        <?php } else { foreach ($recent as $item) { ?>
                            <div class="file-card">
                                <a href="?page=<?php echo rawurlencode($item['page']); ?>" title="<?php echo htmlspecialchars($item['title']); ?>">
                                    <i class="fas fa-file-alt"></i>
                                    <span><?php echo htmlspecialchars($item['title']); ?></span>
                                    <span style="opacity:.75;font-size:.9em;"><?php echo htmlspecialchars($item['ago']); ?></span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php }} ?>
                    </div>
                </div>

                <!-- Getting started -->
                <div class="content-section" style="margin-top:24px;">
                    <h2><i class="fas fa-road"></i> Quick Navigation Tips</h2>
                    <ul style="line-height:1.8; margin-left: 1rem;">
                        <li><strong>Press <kbd>⌘K</kbd> or <kbd>Ctrl+K</kbd></strong> to open the Command Palette and instantly filter across all 400+ guides.</li>
                        <li><strong>Press <kbd>F1</kbd></strong> at any time to see the complete list of keyboard shortcuts (zooming, toggling sidebar, bookmarking).</li>
                        <li><strong>Click any heading</strong> or hover to copy a direct deep-link anchor URL for instant sharing.</li>
                        <li><strong>Click the Copy icon</strong> on any code block to copy formatted syntax with line numbers excluded.</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function getTopCategories() {
        $root = $this->baseDir . '/tutorials';
        $out = [];
        if (!is_dir($root)) return $out;

        if ($handle = opendir($root)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] === '.') continue;
                $full = $root . '/' . $entry;
                if (is_dir($full)) {
                    $count = $this->countMarkdownFiles($full);
                    $out[] = [
                        'title' => ucwords(str_replace(['-', '_'], ' ', $entry)),
                        'page' => $entry,
                        'count' => $count,
                    ];
                }
            }
            closedir($handle);
        }

        usort($out, function($a, $b){
            // Sort by name ascending
            return strcmp($a['title'], $b['title']);
        });

        return $out;
    }

    private function countMarkdownFiles($dir) {
        $count = 0;
        if (!is_dir($dir)) return 0;
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry[0] === '.') continue;
                $full = $dir . '/' . $entry;
                if (is_dir($full)) {
                    $count += $this->countMarkdownFiles($full);
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) === 'md') {
                    $count++;
                }
            }
            closedir($handle);
        }
        return $count;
    }

    private function getRecentTutorials($limit = 6) {
        $all = $this->getAllTutorials();
        foreach ($all as &$t) {
            $t['mtime'] = @filemtime($t['path']) ?: 0;
        }
        unset($t);
        usort($all, function($a, $b){ return $b['mtime'] <=> $a['mtime']; });
        $slice = array_slice($all, 0, $limit);
        foreach ($slice as &$t) {
            $t['ago'] = $this->relativeTime($t['mtime']);
        }
        unset($t);
        return $slice;
    }

    private function getTutorialStats() {
        $all = $this->getAllTutorials();
        $total = count($all);
        $root = $this->baseDir . '/tutorials';
        $categories = 0;
        if (is_dir($root) && ($h = opendir($root))) {
            while (false !== ($e = readdir($h))) {
                if ($e[0] === '.') continue;
                if (is_dir($root . '/' . $e)) $categories++;
            }
            closedir($h);
        }
        $lastUpdated = 0;
        foreach ($all as $t) {
            $lastUpdated = max($lastUpdated, @filemtime($t['path']) ?: 0);
        }
        return [
            'totalTutorials' => $total,
            'totalCategories' => $categories,
            'lastUpdated' => $lastUpdated,
            'lastUpdatedAgo' => $lastUpdated ? $this->relativeTime($lastUpdated) : 'n/a',
        ];
    }

    private function getRandomTutorial() {
        $all = $this->getAllTutorials();
        if (empty($all)) return null;
        $idx = random_int(0, count($all) - 1);
        return $all[$idx];
    }

    private function relativeTime($timestamp) {
        if (!$timestamp) return 'n/a';
        $diff = time() - $timestamp;
        if ($diff < 60) return 'just now';
        $units = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
        ];
        foreach ($units as $secs => $name) {
            if ($diff >= $secs) {
                $val = floor($diff / $secs);
                return $val . ' ' . $name . ($val > 1 ? 's' : '') . ' ago';
            }
        }
        return 'just now';
    }
    
    private function render404() {
        return '
        <div class="content-wrapper">
            <div class="error-page">
                <h1><i class="fas fa-exclamation-triangle"></i> Page Not Found</h1>
                <p>The requested tutorial could not be found.</p>
                <a href="?page=home" class="btn"><i class="fas fa-home"></i> Go Home</a>
            </div>
        </div>';
    }
}

// Initialize and run the application
$app = new MarkdownTutorialApp();
echo $app->run();
?>