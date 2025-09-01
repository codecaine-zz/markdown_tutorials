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
    
    private function renderPage($page) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Markdown Tutorials</title>
            <!-- Highlight.js with comprehensive language support -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
            <link rel="stylesheet" href="assets/css/style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
            <script defer src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
            <!-- Highlight.js common bundle (includes popular languages in a single file) -->
            <script defer src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
        </head>
        <body>
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="container">
                <!-- Sidebar Navigation -->
                <nav class="sidebar" id="sidebar">
                        <!-- Width toggle like Neutralino app -->
                        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Navigation Width">
                            <i class="fas fa-arrows-alt-h"></i>
                        </button>
                    <div class="sidebar-header">
                        <h2><i class="fas fa-book"></i> Tutorials</h2>
                    </div>
                    <div class="sidebar-content">
                            <!-- Sidebar search -->
                            <div class="search-container">
                                <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" id="navigationSearch" placeholder="Search tutorials..." autocomplete="off" />
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
                            <button id="tbHome" class="tb-btn" title="Home"><i class="fas fa-home"></i><span>Home</span></button>
                            <button id="tbCollapseAll" class="tb-btn" title="Collapse all"><i class="fas fa-compress"></i><span>Collapse</span></button>
                            <button id="tbExpandAll" class="tb-btn" title="Expand all"><i class="fas fa-expand"></i><span>Expand</span></button>
                            <button id="tbRefresh" class="tb-btn" title="Refresh navigation"><i class="fas fa-rotate"></i><span>Refresh</span></button>
                        </div>
                        <div class="toolbar-right">
                            <button id="tbTheme" class="tb-btn" title="Toggle theme"><i class="fas fa-moon"></i><span>Theme</span></button>
                            <div class="tb-sep" aria-hidden="true"></div>
                            <button id="tbZoomOut" class="tb-btn" title="Zoom out"><i class="fas fa-search-minus"></i></button>
                            <span id="tbZoomIndicator" class="tb-indicator" aria-live="polite">100%</span>
                            <button id="tbZoomIn" class="tb-btn" title="Zoom in"><i class="fas fa-search-plus"></i></button>
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

            <script src="assets/js/app.js"></script>
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
                $output .= '<div class="tutorial-actions" style="display:flex;gap:10px;margin:10px 0;">';
                $output .= '<button class="btn-secondary" id="tocToggle" onclick="toggleTableOfContents()" title="Toggle table of contents">';
                $output .= '<i class="fas fa-list"></i> Contents';
                $output .= '</button>';
                $output .= '</div>';

                // Table of Contents container (hidden by default)
                $output .= '<div class="table-of-contents" id="tableOfContents" style="display:none;">';
                $output .= '<h3 class="toc-title"><i class="fas fa-list"></i> Table of Contents</h3>';
                $output .= '<ul class="toc-list"></ul>';
                $output .= '</div>';
            
            // Client-side render placeholder
            $output .= '<div class="markdown-content" data-md-src="' . htmlspecialchars($relative) . '">';
            $output .= '<div class="loading">Loading tutorial…</div>';
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
        return '
        <div class="content-wrapper">
            <div class="home-page">
                <h1><i class="fas fa-book-open"></i> Welcome to Markdown Tutorials</h1>
                <p>Navigate through the tutorials using the sidebar on the left. Each tutorial includes:</p>
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-code"></i>
                        <h3>Syntax Highlighting</h3>
                        <p>All code blocks are highlighted with Highlight.js for better readability</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-copy"></i>
                        <h3>Copy to Clipboard</h3>
                        <p>Each code block has a copy button for easy copying</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-sitemap"></i>
                        <h3>Organized Structure</h3>
                        <p>Tutorials are organized in folders and subfolders for easy navigation</p>
                    </div>
                </div>
            </div>
        </div>';
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