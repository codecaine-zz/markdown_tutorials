document.addEventListener('DOMContentLoaded', function() {
    // Normalize marked options to avoid unexpected prefixes on header IDs
    if (window.marked && typeof marked.setOptions === 'function') {
        marked.setOptions({ headerIds: true, headerPrefix: '' });
    }

    // Function to fetch and render Markdown content
    function renderMarkdown() {
        const contentDiv = document.querySelector('.markdown-content');
        if (contentDiv) {
            const markdownSrc = contentDiv.getAttribute('data-md-src');
            if (markdownSrc) {
                fetch(markdownSrc)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(text => {
                        contentDiv.innerHTML = marked.parse(text, { headerIds: true, headerPrefix: '' });
                        // After rendering, ensure headings have stable IDs matching TOC anchors
                        ensureHeadingIds(contentDiv);
                        // Align in-page anchor hrefs with final unique IDs
                        rewriteAnchorHrefs(contentDiv);
                        // After rendering, highlight code blocks
                        hljs.highlightAll();
                        addCopyButtons();
                        wireInPageLinks(contentDiv); // Enable smooth in-page navigation
                        buildTableOfContents(); // Build ToC from headings
                        scrollToAnchor(); // Scroll to anchor after content is rendered
                    })
                    .catch(error => {
                        console.error('Error fetching markdown:', error);
                        contentDiv.innerHTML = '<p>Error loading tutorial. Please try again later.</p>';
                    });
            }
        }
    }

    // Stable GitHub-like slug generator for heading IDs
    function githubSlugify(text) {
        if (!text) return '';
        let slug = text
            .toLowerCase()
            .trim()
            .replace(/&/g, ' and ')
            .replace(/[\u2000-\u206F\u2E00-\u2E7F'"!#$%*+,./:;<=?>@\[\]^`{|}~()]/g, '') // remove punctuation (keep hyphens and underscores handling next)
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        return slug;
    }

    function ensureHeadingIds(root) {
        const used = new Set();
        const headings = root.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headings.forEach(h => {
            let id = h.getAttribute('id');
            if (!id || id.trim() === '') {
                id = githubSlugify(h.textContent || '');
            }
            // Ensure uniqueness in page
            let unique = id;
            let i = 1;
            while (unique && used.has(unique)) {
                unique = `${id}-${i++}`;
            }
            if (unique) {
                h.id = unique;
                used.add(unique);
            }
        });
    }

    // Function to add copy buttons to code blocks
    function addCopyButtons() {
        const codeBlocks = document.querySelectorAll('pre code');
        codeBlocks.forEach(codeBlock => {
            const pre = codeBlock.parentNode;
            if (pre.querySelector('.copy-btn')) {
                return; // Skip if button already exists
            }

            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            copyBtn.setAttribute('aria-label', 'Copy code to clipboard');
            copyBtn.type = 'button';
            
            pre.style.position = 'relative';
            pre.appendChild(copyBtn);

            copyBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const codeToCopy = codeBlock.innerText;
                
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(codeToCopy);
                    } else {
                        // Fallback for older browsers and iOS Safari
                        const textArea = document.createElement('textarea');
                        textArea.value = codeToCopy;
                        textArea.style.position = 'fixed';
                        textArea.style.left = '-9999px';
                        document.body.appendChild(textArea);
                        textArea.select();
                        textArea.setSelectionRange(0, 99999); // For mobile devices
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                    }
                    
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    copyBtn.classList.add('copied');
                    
                    // Provide haptic feedback on supported devices
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                    
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
                        copyBtn.classList.remove('copied');
                    }, 2000);
                    
                } catch (err) {
                    console.error('Failed to copy text: ', err);
                    copyBtn.innerHTML = '<i class="fas fa-times"></i> Failed';
                    copyBtn.classList.add('error');
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
                        copyBtn.classList.remove('error');
                    }, 2000);
                }
            });
            
            // Add touch event support for better mobile interaction
            copyBtn.addEventListener('touchstart', (e) => {
                e.stopPropagation();
            }, { passive: true });
        });
    }

    // Build a Table of Contents from headings
    function buildTableOfContents() {
        const toc = document.getElementById('tableOfContents');
        if (!toc) return;
        const list = toc.querySelector('.toc-list');
        if (!list) return;
        list.innerHTML = '';

        const headings = document.querySelectorAll('.markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4');
        const stack = [{ level: 0, ul: list }];

        headings.forEach(h => {
            const level = parseInt(h.tagName.substring(1), 10);
            const text = h.textContent.trim();
            const id = h.id || githubSlugify(text);

            while (stack[stack.length - 1].level >= level) stack.pop();
            const current = stack[stack.length - 1];

            let ul = current.ul;
            if (current.level + 1 < level) {
                // Skip overly nested jumps; normalize to +1
                const li = ul.lastElementChild || ul.appendChild(document.createElement('li'));
                const newUl = document.createElement('ul');
                newUl.className = 'toc-nested';
                li.appendChild(newUl);
                stack.push({ level: current.level + 1, ul: newUl });
            }

            if (current.level + 1 === level) {
                // same expected nesting
            } else if (current.level !== level) {
                // Create nested container
                const li = ul.lastElementChild || ul.appendChild(document.createElement('li'));
                const newUl = document.createElement('ul');
                newUl.className = 'toc-nested';
                li.appendChild(newUl);
                stack.push({ level, ul: newUl });
            }

            const targetUl = stack[stack.length - 1].ul;
            const li = document.createElement('li');
            li.className = `toc-item toc-level-${level}`;
            const a = document.createElement('a');
            a.className = 'toc-link';
            a.textContent = text;
            a.href = `#${id}`;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const el = getAnchorTarget(id);
                if (el) {
                    window.__lastAnchorScrollY = window.scrollY;
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    showInlinePreviousButton(el);
                    if (history.pushState) history.pushState(null, '', `#${id}`);
                }
            });
            li.appendChild(a);
            targetUl.appendChild(li);
        });
    }

    function getAnchorTarget(id) {
        if (!id) return null;
        // Exact match
        let el = document.getElementById(id);
        if (el) return el;
        // GitHub-style prefix sometimes added by renderers
        el = document.getElementById(`user-content-${id}`);
        if (el) return el;
        // Fallback: find a heading whose slugified text matches
        const headings = document.querySelectorAll('.markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4, .markdown-content h5, .markdown-content h6');
        for (const h of headings) {
            if (githubSlugify(h.textContent || '') === id) return h;
        }
        return null;
    }

    // Function to scroll to an anchor if present in the URL
    function scrollToAnchor() {
        const hash = decodeURIComponent(window.location.hash || '');
        if (hash && hash.startsWith('#')) {
            const id = hash.slice(1);
            const before = window.scrollY;
            const element = getAnchorTarget(id);
            if (element) {
                window.__lastAnchorScrollY = before;
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                showInlinePreviousButton(element);
            }
        }
    }

    // Inline previous button and highlight on anchor target
    function showInlinePreviousButton(target) {
        if (!target) return;
        // Add highlight
        target.classList.add('anchor-highlight');
        setTimeout(() => target.classList.remove('anchor-highlight'), 1500);

        // Avoid duplicating buttons per target
        if (target.querySelector('.anchor-prev-btn')) return;

        const btn = document.createElement('button');
        btn.className = 'anchor-prev-btn';
        btn.type = 'button';
        btn.title = 'Go back to previous position';
        btn.innerHTML = '<i class="fas fa-arrow-up"></i> Previous';
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.__lastAnchorScrollY !== undefined && window.__lastAnchorScrollY !== null) {
                window.scrollTo({ top: window.__lastAnchorScrollY, behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        const firstChild = target.firstElementChild;
        if (firstChild && firstChild.tagName.toLowerCase() === 'a') {
            firstChild.insertAdjacentElement('afterend', btn);
        } else {
            target.appendChild(btn);
        }

        setTimeout(() => { btn.remove(); }, 8000);
    }

    // Delegate in-page anchor clicks inside markdown so they scroll smoothly
    function wireInPageLinks(root) {
        root.addEventListener('click', (e) => {
            const a = e.target.closest('a[href^="#"]');
            if (!a) return;
            const href = a.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const id = decodeURIComponent(href.slice(1));
                if (id) {
                    const el = getAnchorTarget(id);
                    window.__lastAnchorScrollY = window.scrollY;
                    if (el) {
                        if (window.location.hash !== `#${id}`) {
                            history.pushState(null, '', `#${id}`);
                        }
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        showInlinePreviousButton(el);
                    } else {
                        history.pushState(null, '', `#${id}`);
                    }
                }
            }
        });
    }

    // Sidebar folder toggle functionality with proper nesting support
    function initFolderToggles() {
        const folderHeaders = document.querySelectorAll('.nav-folder-header');
        folderHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                toggleFolder(this);
            });
        });
    }

    function toggleFolder(header) {
        const folderContent = header.nextElementSibling;
        const folderArrow = header.querySelector('.folder-arrow');
        
        if (folderContent && folderContent.classList.contains('nav-folder-content')) {
            if (folderContent.classList.contains('open')) {
                // Close folder
                folderContent.classList.remove('open');
                folderContent.style.maxHeight = '0px';
                folderArrow.classList.remove('open');
            } else {
                // Open folder
                folderContent.classList.add('open');
                folderArrow.classList.add('open');
                
                // Calculate the full height including nested open folders
                updateFolderHeight(folderContent);
            }
            
            // Update parent folder heights if this is a nested folder
            updateParentFolderHeights(header);
        }
    }

    function updateFolderHeight(folderContent) {
        if (!folderContent) return;
        
        // Temporarily set height to auto to measure actual content
        const originalMaxHeight = folderContent.style.maxHeight;
        const originalOverflow = folderContent.style.overflow;
        
        folderContent.style.maxHeight = 'none';
        folderContent.style.overflow = 'visible';
        
        const height = folderContent.scrollHeight;
        
        folderContent.style.maxHeight = originalMaxHeight;
        folderContent.style.overflow = originalOverflow;
        
        // Set the calculated height
        folderContent.style.maxHeight = Math.max(height, 0) + 'px';
    }

    function updateParentFolderHeights(element) {
        let parent = element.closest('.nav-folder-content');
        while (parent) {
            if (parent.classList.contains('open')) {
                updateFolderHeight(parent);
            }
            parent = parent.parentElement.closest('.nav-folder-content');
        }
    }

    function expandCurrentPath() {
        // Find the currently active nav item and expand its parent folders
        const activeItem = document.querySelector('.nav-item a.active');
        if (activeItem) {
            let parent = activeItem.closest('.nav-folder-content');
            while (parent) {
                if (!parent.classList.contains('open')) {
                    parent.classList.add('open');
                    parent.style.maxHeight = 'none';
                    
                    // Find the corresponding header and update its arrow
                    const header = parent.previousElementSibling;
                    if (header && header.classList.contains('nav-folder-header')) {
                        const arrow = header.querySelector('.folder-arrow');
                        if (arrow) {
                            arrow.classList.add('open');
                        }
                    }
                }
                parent = parent.parentElement.closest('.nav-folder-content');
            }
        }
    }

    // Function to initialize all folder states correctly
    function initializeFolderStates() {
        // Ensure all initially open folders have proper height
        const openFolders = document.querySelectorAll('.nav-folder-content.open');
        openFolders.forEach(folder => {
            folder.style.maxHeight = 'none';
        });
    }

    // Enhanced breadcrumb functionality
    function initializeBreadcrumbEnhancements() {
        const breadcrumbPath = document.querySelector('.breadcrumb-path');
        if (!breadcrumbPath) return;

        // Add responsive handling for long paths
        const pathElements = breadcrumbPath.children.length;
        if (pathElements > 6) { // If more than 3 levels (home + 3 actual levels = 6 elements with separators)
            breadcrumbPath.classList.add('long-path');
        }

        // Add smooth hover effects and keyboard navigation
        const breadcrumbLinks = document.querySelectorAll('.breadcrumb-link');
        breadcrumbLinks.forEach(link => {
            // Add keyboard support
            link.setAttribute('role', 'button');
            link.setAttribute('tabindex', '0');
            
            // Handle keyboard navigation
            link.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    link.click();
                }
            });

            // Add visual feedback on interaction
            link.addEventListener('click', (e) => {
                // Add a subtle click animation
                link.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    link.style.transform = '';
                }, 100);
            });
        });

        // Add tooltip functionality for truncated text on mobile
        if (window.innerWidth <= 480) {
            breadcrumbLinks.forEach(link => {
                if (link.scrollWidth > link.clientWidth) {
                    link.setAttribute('title', link.textContent);
                }
            });
        }
    }

    // Initialize folder toggles and expand current path
    initializeFolderStates();
    initFolderToggles();
    expandCurrentPath();
    initializeBreadcrumbEnhancements();

    // Mobile sidebar toggle
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
    // On mobile, slide in/out using 'open' class to match CSS
    sidebar.classList.toggle('open');
    }

    // History navigation
    window.goBack = function() {
        window.history.back();
    }

    window.goForward = function() {
        window.history.forward();
    }

    // Initial render
    renderMarkdown();

    // Handle hash changes for in-page navigation
    window.addEventListener('hashchange', scrollToAnchor);

    function rewriteAnchorHrefs(root) {
        const map = new Map();
        root.querySelectorAll('h1, h2, h3, h4, h5, h6').forEach(h => {
            map.set(githubSlugify(h.textContent || ''), h.id);
        });
        root.querySelectorAll('a[href^="#"]').forEach(a => {
            const href = a.getAttribute('href');
            const rawId = decodeURIComponent(href.slice(1));
            // If there's no exact element for this id, try mapping via slug
            if (!document.getElementById(rawId) && !document.getElementById(`user-content-${rawId}`)) {
                const mapped = map.get(rawId);
                if (mapped) {
                    a.setAttribute('href', `#${mapped}`);
                }
            }
        });
    }

    // Sidebar width toggle (expanded/collapsed)
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('expanded')) {
                sidebar.classList.remove('expanded');
                sidebar.classList.add('collapsed');
                sidebarToggle.querySelector('i').className = 'fas fa-arrows-alt-h';
                saveUiState();
            } else if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                sidebarToggle.querySelector('i').className = 'fas fa-arrows-alt-h';
                saveUiState();
            } else {
                sidebar.classList.add('expanded');
                sidebarToggle.querySelector('i').className = 'fas fa-compress-alt';
                saveUiState();
            }
        });
    }

    // Sidebar search filter
    const searchInput = document.getElementById('navigationSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    function filterNav(query) {
        const items = document.querySelectorAll('#nav-items .nav-item, #nav-items .nav-folder, #nav-items .nav-folder-header');
        if (!items.length) return;
        const q = (query || '').trim().toLowerCase();
        if (!q) {
            // reset
            document.querySelectorAll('#nav-items .nav-item, #nav-items .nav-folder').forEach(el => el.style.display = '');
            return;
        }
        // Hide all then show matches and their ancestors
        document.querySelectorAll('#nav-items .nav-item, #nav-items .nav-folder').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#nav-items .nav-item a, #nav-items .nav-folder-header span').forEach(el => {
            const text = (el.textContent || '').toLowerCase();
            if (text.includes(q)) {
                const block = el.closest('.nav-item, .nav-folder');
                if (block) block.style.display = '';
                // Show ancestors
                let parent = el.closest('.nav-folder-content');
                while (parent) {
                    parent.style.display = '';
                    parent.classList.add('open');
                    parent.style.maxHeight = 'none';
                    const header = parent.previousElementSibling;
                    if (header && header.classList.contains('nav-folder-header')) {
                        const arrow = header.querySelector('.folder-arrow');
                        arrow && arrow.classList.add('open');
                        header.parentElement && (header.parentElement.style.display = '');
                    }
                    parent = parent.parentElement?.closest('.nav-folder-content');
                }
            }
        });
    }
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const v = e.target.value;
            filterNav(v);
            if (clearSearchBtn) clearSearchBtn.style.opacity = v ? '1' : '0';
        });
    }
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            filterNav('');
            clearSearchBtn.style.opacity = '0';
        });
    }

    // ToC toggle
    window.toggleTableOfContents = function() {
        const toc = document.getElementById('tableOfContents');
        if (!toc) return;
        const isHidden = toc.style.display === 'none' || toc.style.display === '';
        toc.style.display = isHidden ? 'block' : 'none';
    }

    // ===== Toolbar wiring =====
    const busy = {
        el: document.getElementById('busyOverlay'),
        show() { this.el && this.el.classList.add('show'); },
        hide() { this.el && this.el.classList.remove('show'); }
    };

    const TB = {
        home: document.getElementById('tbHome'),
        collapseAll: document.getElementById('tbCollapseAll'),
        expandAll: document.getElementById('tbExpandAll'),
        refresh: document.getElementById('tbRefresh'),
        theme: document.getElementById('tbTheme'),
        zoomOut: document.getElementById('tbZoomOut'),
        zoomIn: document.getElementById('tbZoomIn'),
        zoomIndicator: document.getElementById('tbZoomIndicator')
    };

    TB.home && TB.home.addEventListener('click', () => { window.location.href = '?page=home'; });

    TB.collapseAll && TB.collapseAll.addEventListener('click', () => {
        busy.show();
        setTimeout(() => {
            document.querySelectorAll('.nav-folder-content.open').forEach(f => { f.classList.remove('open'); f.style.maxHeight = '0px'; });
            document.querySelectorAll('.nav-folder-header .folder-arrow.open').forEach(a => a.classList.remove('open'));
            saveFolderStates();
            busy.hide();
        }, 150);
    });

    TB.expandAll && TB.expandAll.addEventListener('click', () => {
        busy.show();
        setTimeout(() => {
            document.querySelectorAll('.nav-folder-content').forEach(f => { f.classList.add('open'); f.style.maxHeight = 'none'; });
            document.querySelectorAll('.nav-folder-header .folder-arrow').forEach(a => a.classList.add('open'));
            saveFolderStates();
            busy.hide();
        }, 150);
    });

    TB.refresh && TB.refresh.addEventListener('click', () => {
        busy.show();
        setTimeout(() => {
            // Rebuild TOC and re-apply search filters; nav is server-rendered, so just reset states
            initializeFolderStates();
            initFolderToggles();
            expandCurrentPath();
            buildTableOfContents();
            busy.hide();
        }, 120);
    });

    // Theme toggle with persistence
    const THEME_KEY = 'mt_theme';
    function applyTheme(theme) {
        const root = document.documentElement;
        if (theme === 'dark') {
            root.classList.add('theme-dark');
            root.classList.remove('force-light');
            TB.theme && (TB.theme.querySelector('i').className = 'fas fa-sun');
        } else {
            root.classList.remove('theme-dark');
            root.classList.add('force-light');
            TB.theme && (TB.theme.querySelector('i').className = 'fas fa-moon');
        }
        localStorage.setItem(THEME_KEY, theme);
    }
    function loadTheme() {
        const saved = localStorage.getItem(THEME_KEY);
        if (saved) applyTheme(saved);
    }
    TB.theme && TB.theme.addEventListener('click', () => {
        const isDark = document.documentElement.classList.contains('theme-dark');
        applyTheme(isDark ? 'light' : 'dark');
    });
    loadTheme();

    // Zoom controls with persistence
    const ZOOM_KEY = 'mt_zoom';
    const zoomContainer = document.getElementById('contentZoom');
    let zoom = 1.0;
    function applyZoom() {
        if (!zoomContainer) return;
        zoomContainer.style.transform = `scale(${zoom})`;
        TB.zoomIndicator && (TB.zoomIndicator.textContent = `${Math.round(zoom*100)}%`);
        localStorage.setItem(ZOOM_KEY, String(zoom));
    }
    function loadZoom() {
        const v = parseFloat(localStorage.getItem(ZOOM_KEY) || '1');
        if (!Number.isNaN(v)) {
            zoom = Math.min(1.8, Math.max(0.8, v));
            applyZoom();
        }
    }
    TB.zoomIn && TB.zoomIn.addEventListener('click', () => { zoom = Math.min(1.8, zoom + 0.1); applyZoom(); });
    TB.zoomOut && TB.zoomOut.addEventListener('click', () => { zoom = Math.max(0.8, zoom - 0.1); applyZoom(); });
    loadZoom();

    // Persist sidebar and folder states
    const UI_KEY = 'mt_ui_state';
    function saveFolderStates() {
        const states = [];
        document.querySelectorAll('.nav-folder').forEach((folder, idx) => {
            const content = folder.querySelector(':scope > .nav-folder-content');
            if (content) states.push({ idx, open: content.classList.contains('open') });
        });
        const data = loadUiState();
        data.folders = states;
        localStorage.setItem(UI_KEY, JSON.stringify(data));
    }
    function restoreFolderStates() {
        try {
            const data = loadUiState();
            if (!data.folders) return;
            const folders = Array.from(document.querySelectorAll('.nav-folder'));
            data.folders.forEach(s => {
                const f = folders[s.idx];
                if (!f) return;
                const content = f.querySelector(':scope > .nav-folder-content');
                const header = f.querySelector(':scope > .nav-folder-header .folder-arrow');
                if (content) {
                    if (s.open) {
                        content.classList.add('open');
                        content.style.maxHeight = 'none';
                        header && header.classList.add('open');
                    } else {
                        content.classList.remove('open');
                        content.style.maxHeight = '0px';
                        header && header.classList.remove('open');
                    }
                }
            });
        } catch {}
    }
    function saveUiState() {
        const sidebar = document.getElementById('sidebar');
        const data = loadUiState();
        data.sidebar = {
            expanded: sidebar.classList.contains('expanded'),
            collapsed: sidebar.classList.contains('collapsed')
        };
        localStorage.setItem(UI_KEY, JSON.stringify(data));
        return data;
    }
    function loadUiState() {
        try { return JSON.parse(localStorage.getItem(UI_KEY) || '{}'); } catch { return {}; }
    }
    function restoreUiState() {
        const data = loadUiState();
        const sidebar = document.getElementById('sidebar');
        if (data.sidebar) {
            sidebar.classList.toggle('expanded', !!data.sidebar.expanded);
            sidebar.classList.toggle('collapsed', !!data.sidebar.collapsed);
        }
        restoreFolderStates();
    }
    restoreUiState();

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        const cmd = e.metaKey || e.ctrlKey;
        if (e.key === 'F1') { e.preventDefault(); alert('Shortcuts:\n• Cmd/Ctrl+F: focus search\n• Cmd/Ctrl+B: toggle sidebar width\n• Cmd/Ctrl+H: home\n• +/-: zoom'); }
        if (cmd && (e.key === 'f' || e.key === 'F')) {
            e.preventDefault();
            const inp = document.getElementById('navigationSearch');
            inp && inp.focus();
        }
        if (cmd && (e.key === 'b' || e.key === 'B')) {
            e.preventDefault();
            sidebarToggle && sidebarToggle.click();
        }
        if (cmd && (e.key === 'h' || e.key === 'H')) {
            e.preventDefault();
            TB.home && TB.home.click();
        }
        if ((e.key === '+' || e.key === '=')) { e.preventDefault(); TB.zoomIn && TB.zoomIn.click(); }
        if ((e.key === '-') ) { e.preventDefault(); TB.zoomOut && TB.zoomOut.click(); }
    });
});
