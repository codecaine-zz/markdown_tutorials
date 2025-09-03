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
                        initTocScrollSpy(); // Highlight active section while scrolling
                        scrollToAnchor(); // Scroll to anchor after content is rendered

                        // Update document title from first heading and record correct history entry
                        try {
                            const h = contentDiv.querySelector('h1, h2');
                            const pageTitle = (h ? h.textContent : '').trim();
                            if (pageTitle) {
                                setDocumentTitle(pageTitle);
                                addOrUpdateHistory(pageTitle);
                            }
                        } catch {}
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

    // ScrollSpy: mark active ToC link based on headings in view
    function initTocScrollSpy() {
        const toc = document.getElementById('tableOfContents');
        if (!toc) return;
        const links = Array.from(toc.querySelectorAll('.toc-link'));
        if (!links.length) return;

        const idToLink = new Map();
        links.forEach(a => {
            const id = decodeURIComponent((a.getAttribute('href') || '').replace('#',''));
            if (id) idToLink.set(id, a);
        });

        const headings = Array.from(document.querySelectorAll('.markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4'))
            .filter(h => h.id);

        let ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                const fromTop = window.scrollY + 90; // offset for toolbar, matches CSS top
                let active = null;
                for (const h of headings) {
                    if (h.offsetTop <= fromTop) active = h;
                    else break;
                }
                links.forEach(a => a.classList.remove('active'));
                if (active) {
                    const link = idToLink.get(active.id) || idToLink.get(`user-content-${active.id}`);
                    link && link.classList.add('active');
                }
                ticking = false;
            });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
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

        // Remove any existing previous buttons from other headings
        const existingButtons = document.querySelectorAll('.anchor-prev-btn');
        existingButtons.forEach(btn => btn.remove());

        const btn = document.createElement('button');
        btn.className = 'anchor-prev-btn';
        btn.type = 'button';
        btn.title = 'Go back to previous position';
        btn.innerHTML = '<i class="fas fa-arrow-up"></i> Previous';
        
        // Add click handler that removes the button after use
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.__lastAnchorScrollY !== undefined && window.__lastAnchorScrollY !== null) {
                window.scrollTo({ top: window.__lastAnchorScrollY, behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            // Remove button after it's been used
            btn.remove();
        });

        const firstChild = target.firstElementChild;
        if (firstChild && firstChild.tagName.toLowerCase() === 'a') {
            firstChild.insertAdjacentElement('afterend', btn);
        } else {
            target.appendChild(btn);
        }
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

    // Enhanced sidebar search filter with fuzzy search and bookmarks
    const searchInput = document.getElementById('navigationSearch');
    const clearSearchBtn = document.getElementById('clearSearch');
    let allNavItems = []; // Cache for search
    
    function cacheNavItems() {
        allNavItems = [];
        const links = document.querySelectorAll('#nav-items .nav-item a');
        links.forEach(link => {
            const span = link.querySelector('span');
            if (span) {
                const folderPath = [];
                let parent = link.closest('.nav-folder-content');
                while (parent) {
                    const header = parent.previousElementSibling;
                    if (header && header.classList.contains('nav-folder-header')) {
                        const headerSpan = header.querySelector('span');
                        if (headerSpan) folderPath.unshift(headerSpan.textContent);
                    }
                    parent = parent.parentElement?.closest('.nav-folder-content');
                }
                
                allNavItems.push({
                    element: link.closest('.nav-item'),
                    link: link,
                    title: span.textContent,
                    path: folderPath.join(' > '),
                    fullPath: [...folderPath, span.textContent].join(' ').toLowerCase(),
                    href: link.href
                });
            }
        });
    }
    
    function enhancedFilterNav(query) {
        if (!allNavItems.length) cacheNavItems();
        
        const q = (query || '').trim().toLowerCase();
        const searchContainer = document.getElementById('nav-items');
        
        if (!q) {
            // Reset to original navigation
            searchContainer.innerHTML = originalNavContent;
            initFolderToggles();
            expandCurrentPath();
            initializeFolderStates();
            return;
        }
        
        // Fuzzy search
        const results = allNavItems
            .map(item => ({
                ...item,
                score: calculateSearchScore(item.title.toLowerCase(), item.fullPath, q)
            }))
            .filter(item => item.score > 0)
            .sort((a, b) => b.score - a.score);
        
        // Save search term
        saveSearchTerm(query);
        
        // Render search results
        if (results.length === 0) {
            searchContainer.innerHTML = `
                <div class="search-no-results">
                    <i class="fas fa-search"></i>
                    <p>No tutorials found</p>
                    <small>Try different keywords</small>
                </div>
            `;
        } else {
            let html = `<div class="search-results-header">
                <i class="fas fa-search"></i>
                <span>${results.length} result${results.length !== 1 ? 's' : ''}</span>
            </div>`;
            
            results.forEach(item => {
                const highlightedTitle = highlightSearchTerm(item.title, q);
                const isBookmarked = isItemBookmarked(item.href);
                html += `
                    <div class="search-result-item">
                        <a href="${item.href}">
                            <i class="fas fa-file-alt"></i>
                            <div class="search-result-content">
                                <div class="search-result-title">${highlightedTitle}</div>
                                ${item.path ? `<div class="search-result-path">${item.path}</div>` : ''}
                            </div>
                            ${isBookmarked ? '<i class="fas fa-bookmark bookmark-icon"></i>' : ''}
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                `;
            });
            
            searchContainer.innerHTML = html;
        }
    }
    
    function calculateSearchScore(title, fullPath, query) {
        let score = 0;
        
        // Exact title match gets highest score
        if (title === query) return 1000;
        if (title.includes(query)) return 500 + (100 - title.length);
        
        // Full path match
        if (fullPath.includes(query)) score += 200;
        
        // Word boundary matches
        const words = query.split(' ');
        words.forEach(word => {
            if (word.length > 2) {
                const regex = new RegExp('\\b' + escapeRegExp(word), 'i');
                if (regex.test(title)) score += 50;
                if (regex.test(fullPath)) score += 25;
            }
        });
        
        // Fuzzy match
        let queryIndex = 0;
        let consecutiveMatches = 0;
        for (let i = 0; i < title.length && queryIndex < query.length; i++) {
            if (title[i] === query[queryIndex]) {
                score += 2;
                consecutiveMatches++;
                queryIndex++;
            } else {
                if (consecutiveMatches > 2) score += consecutiveMatches * 5;
                consecutiveMatches = 0;
            }
        }
        
        return queryIndex === query.length ? score : 0;
    }
    
    function highlightSearchTerm(text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${escapeRegExp(term)})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    // Save/load search history
    function saveSearchTerm(term) {
        if (!term.trim()) return;
        try {
            let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
            history = history.filter(t => t !== term);
            history.unshift(term);
            history = history.slice(0, 10);
            localStorage.setItem('searchHistory', JSON.stringify(history));
        } catch (e) {}
    }
    
    const originalNavContent = document.getElementById('nav-items')?.innerHTML || '';
    
    if (searchInput) {
        // Cache nav items on load
        setTimeout(cacheNavItems, 100);
        
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const v = e.target.value;
                enhancedFilterNav(v);
                if (clearSearchBtn) clearSearchBtn.style.opacity = v ? '1' : '0';
            }, 200);
        });
        
        // Add search suggestions on focus
        searchInput.addEventListener('focus', showSearchSuggestions);
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            enhancedFilterNav('');
            clearSearchBtn.style.opacity = '0';
            hideSearchSuggestions();
        });
    }
    
    // Search suggestions
    function showSearchSuggestions() {
        if (searchInput.value.trim()) return;
        
        try {
            const history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
            if (history.length === 0) return;
            
            const suggestions = document.createElement('div');
            suggestions.className = 'search-suggestions';
            suggestions.innerHTML = `
                <div class="suggestions-header">Recent searches:</div>
                ${history.slice(0, 5).map(term => 
                    `<div class="suggestion-item" tabindex="0">${term}</div>`
                ).join('')}
            `;
            
            const container = searchInput.closest('.search-container');
            container.appendChild(suggestions);
            
            // Add click handlers
            suggestions.addEventListener('click', (e) => {
                if (e.target.classList.contains('suggestion-item')) {
                    searchInput.value = e.target.textContent;
                    enhancedFilterNav(e.target.textContent);
                    hideSearchSuggestions();
                    clearSearchBtn.style.opacity = '1';
                }
            });
            
            // Hide on outside click
            setTimeout(() => {
                document.addEventListener('click', hideSuggestionsOnOutsideClick);
            }, 10);
            
        } catch (e) {}
    }
    
    function hideSearchSuggestions() {
        document.querySelectorAll('.search-suggestions').forEach(el => el.remove());
        document.removeEventListener('click', hideSuggestionsOnOutsideClick);
    }
    
    function hideSuggestionsOnOutsideClick(e) {
        if (!e.target.closest('.search-container')) {
            hideSearchSuggestions();
        }
    }

    // ToC toggle
    window.toggleTableOfContents = function() {
        const toc = document.getElementById('tableOfContents');
        if (!toc) return;
        
    const isHidden = toc.style.display === 'none';
    const nowVisible = isHidden; // after toggle, it'll be visible if it was hidden
    toc.style.display = isHidden ? 'block' : 'none';
    try { localStorage.setItem('tocVisible', String(nowVisible)); } catch {}
        
        // Update button text based on new state
        const toggleBtn = document.getElementById('tocToggle');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('i');
            const text = toggleBtn.childNodes[toggleBtn.childNodes.length - 1];
            if (isHidden) {
                // Now showing
                if (icon) icon.className = 'fas fa-list';
                if (text) text.textContent = ' Contents';
                toggleBtn.title = 'Hide table of contents';
            } else {
                // Now hiding
                if (icon) icon.className = 'fas fa-list-ul';
                if (text) text.textContent = ' Show Contents';
                toggleBtn.title = 'Show table of contents';
            }
        }
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
        help: document.getElementById('tbHelp'),
        theme: document.getElementById('tbTheme'),
        zoomOut: document.getElementById('tbZoomOut'),
        zoomIn: document.getElementById('tbZoomIn'),
        zoomIndicator: document.getElementById('tbZoomIndicator')
    };

    TB.home && TB.home.addEventListener('click', () => { window.location.href = '?page=home'; });

    TB.help && TB.help.addEventListener('click', showKeyboardShortcuts);

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
    let zoomTarget = null;
    function resolveZoomTarget() {
        // Prefer zooming the markdown article only, so ToC stays sticky
        zoomTarget = document.querySelector('.markdown-content')
            || document.querySelector('#contentZoom .content-wrapper')
            || document.getElementById('contentZoom');
    }
    let zoom = 1.0;
    function applyZoom() {
        if (!zoomTarget) resolveZoomTarget();
        if (!zoomTarget) return;
        zoomTarget.style.setProperty('--md-scale', String(zoom));
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
        // Let F1 be handled by the enhanced handler below to show the modal
        // Don't intercept Cmd/Ctrl+F so the browser's Find-in-page works
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

    // Initialize enhanced features when content is loaded
    if (document.querySelector('.markdown-content')) {
        initEnhancedFeatures();
    }
});

// Enhanced UI Features - Bookmark System & Reading Progress
function initEnhancedFeatures() {
    addBookmarkButton();
    updateBookmarkButton();
    trackReadingProgress();
    showReadingProgress();
    addQuickActionsMenu();
    initRecentHistory();
    restorePreferences();
}

function addBookmarkButton() {
    const actions = document.querySelector('.tutorial-actions');
    if (!actions || document.getElementById('bookmarkBtn')) return;
    
    const bookmarkBtn = document.createElement('button');
    bookmarkBtn.className = 'btn-secondary bookmark-btn';
    bookmarkBtn.id = 'bookmarkBtn';
    bookmarkBtn.innerHTML = '<i class="fas fa-bookmark"></i> Bookmark';
    bookmarkBtn.title = 'Bookmark this tutorial';
    
    bookmarkBtn.addEventListener('click', toggleBookmark);
    actions.appendChild(bookmarkBtn);
}

function toggleBookmark() {
    const currentUrl = window.location.href;
    // Get the actual tutorial name from the page content instead of document.title
    const titleElement = document.querySelector('.markdown-content h1');
    const currentTitle = titleElement ? titleElement.textContent.trim() : 
                        document.querySelector('title') ? document.querySelector('title').textContent.trim() :
                        'Markdown Tutorial';
    const currentPage = new URLSearchParams(window.location.search).get('page') || 'home';
    
    if (isItemBookmarked(currentUrl)) {
        removeBookmark(currentUrl);
        showNotification('Bookmark removed', 'success');
    } else {
        addBookmark(currentUrl, currentTitle, currentPage);
        showNotification('Tutorial bookmarked', 'success');
    }
    updateBookmarkButton();
}

function isItemBookmarked(url) {
    try {
        const bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
        return bookmarks.some(b => b.url === url);
    } catch {
        return false;
    }
}

function addBookmark(url, title, page) {
    try {
        let bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
        bookmarks = bookmarks.filter(b => b.url !== url);
        bookmarks.unshift({
            url,
            title,
            page,
            timestamp: Date.now(),
            progress: getReadingProgress()
        });
        bookmarks = bookmarks.slice(0, 50);
        localStorage.setItem('bookmarks', JSON.stringify(bookmarks));
    } catch (e) {}
}

function removeBookmark(url) {
    try {
        let bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
        bookmarks = bookmarks.filter(b => b.url !== url);
        localStorage.setItem('bookmarks', JSON.stringify(bookmarks));
    } catch (e) {}
}

function updateBookmarkButton() {
    const btn = document.getElementById('bookmarkBtn');
    if (!btn) return;
    
    const isBookmarked = isItemBookmarked(window.location.href);
    
    if (isBookmarked) {
        btn.classList.add('bookmarked');
        btn.innerHTML = '<i class="fas fa-bookmark"></i> Bookmarked';
        btn.title = 'Remove bookmark';
    } else {
        btn.classList.remove('bookmarked');
        btn.innerHTML = '<i class="far fa-bookmark"></i> Bookmark';
        btn.title = 'Bookmark this tutorial';
    }
}

// Reading Progress Tracking
function trackReadingProgress() {
    if (!document.querySelector('.markdown-content')) return;
    
    let scrollTimer;
    const page = new URLSearchParams(window.location.search).get('page') || 'home';
    
    function updateProgress() {
        const progress = calculateReadingProgress();
        saveReadingProgress(page, progress);
    }
    
    window.addEventListener('scroll', () => {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(updateProgress, 500);
    });
    
    window.addEventListener('beforeunload', updateProgress);
}

function calculateReadingProgress() {
    const content = document.querySelector('.markdown-content');
    if (!content) return 0;
    
    const contentRect = content.getBoundingClientRect();
    const windowHeight = window.innerHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    const contentStart = scrollTop + contentRect.top;
    const contentHeight = contentRect.height;
    const viewportBottom = scrollTop + windowHeight;
    
    if (viewportBottom <= contentStart) return 0;
    if (scrollTop >= contentStart + contentHeight) return 100;
    
    const progress = Math.round(((viewportBottom - contentStart) / contentHeight) * 100);
    return Math.min(100, Math.max(0, progress));
}

function saveReadingProgress(page, progress) {
    try {
        let progressData = JSON.parse(localStorage.getItem('readingProgress') || '{}');
        progressData[page] = {
            progress,
            timestamp: Date.now(),
            url: window.location.href
        };
        localStorage.setItem('readingProgress', JSON.stringify(progressData));
    } catch (e) {}
}

function getReadingProgress() {
    const page = new URLSearchParams(window.location.search).get('page') || 'home';
    try {
        const progressData = JSON.parse(localStorage.getItem('readingProgress') || '{}');
        return progressData[page]?.progress || 0;
    } catch {
        return 0;
    }
}

function showReadingProgress() {
    const progress = getReadingProgress();
    if (progress > 10) {
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress-indicator';
        progressBar.innerHTML = `
            <div class="progress-bar">
                <div class="progress-fill" style="width: ${progress}%"></div>
            </div>
            <span class="progress-text">${progress}% completed</span>
        `;
        
        const actions = document.querySelector('.tutorial-actions');
        if (actions) {
            actions.appendChild(progressBar);
        }
    }
}

// Quick Actions Menu
function addQuickActionsMenu() {
    if (document.querySelector('.quick-actions-fab')) return; // Already exists
    
    const fab = document.createElement('div');
    fab.className = 'quick-actions-fab';
    fab.innerHTML = `
        <button class="fab-main" title="Quick Actions">
            <i class="fas fa-plus"></i>
        </button>
        <div class="fab-menu">
            <button class="fab-action" data-action="bookmarks" title="View Bookmarks">
                <i class="fas fa-bookmark"></i>
            </button>
            <button class="fab-action" data-action="history" title="Recent Pages">
                <i class="fas fa-history"></i>
            </button>
            <button class="fab-action" data-action="print" title="Print Tutorial">
                <i class="fas fa-print"></i>
            </button>
            <button class="fab-action" data-action="share" title="Share Tutorial">
                <i class="fas fa-share"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(fab);
    
    const mainBtn = fab.querySelector('.fab-main');
    const menu = fab.querySelector('.fab-menu');
    let isOpen = false;
    
    mainBtn.addEventListener('click', () => {
        isOpen = !isOpen;
        fab.classList.toggle('open', isOpen);
        mainBtn.querySelector('i').className = isOpen ? 'fas fa-times' : 'fas fa-plus';
    });
    
    // Handle quick actions
    fab.addEventListener('click', (e) => {
        const action = e.target.closest('.fab-action')?.dataset.action;
        if (!action) return;
        
        switch (action) {
            case 'bookmarks':
                showBookmarksModal();
                break;
            case 'history':
                showHistoryModal();
                break;
            case 'print':
                window.print();
                break;
            case 'share':
                shareCurrentPage();
                break;
        }
        
        // Close menu after action
        isOpen = false;
        fab.classList.remove('open');
        mainBtn.querySelector('i').className = 'fas fa-plus';
    });
    
    // Close on outside click
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.quick-actions-fab') && isOpen) {
            isOpen = false;
            fab.classList.remove('open');
            mainBtn.querySelector('i').className = 'fas fa-plus';
        }
    });
}

// Recent History Tracking
function initRecentHistory() {
    if (!window.location.search) return;
    // For non-markdown pages (home, folder listings, 404), derive title from server-rendered H1
    const isMarkdownPage = !!document.querySelector('.markdown-content');
    if (!isMarkdownPage) {
        const h = document.querySelector('.content-wrapper h1');
        const title = (h ? h.textContent : '').trim();
        if (title) {
            setDocumentTitle(title);
            addOrUpdateHistory(title);
        } else {
            // Fallback to document.title if no H1
            addOrUpdateHistory(document.title || 'Markdown Tutorials');
        }
    }
}

function addToHistory() {
    const page = new URLSearchParams(window.location.search).get('page');
    if (!page) return;
    
    try {
        // Prefer in-content titles when available
        const mdH1 = document.querySelector('.markdown-content h1');
        const srvH1 = document.querySelector('.content-wrapper h1');
        const derived = (mdH1?.textContent || srvH1?.textContent || document.title || '').trim();
        addOrUpdateHistory(derived || 'Markdown Tutorials');
    } catch (e) {}
}

// Helper: set document.title with consistent branding
function setDocumentTitle(pageTitle) {
    try {
        const base = 'Markdown Tutorials';
        const clean = String(pageTitle || '').replace(/\s+/g, ' ').trim();
        document.title = clean ? `${clean} - ${base}` : base;
    } catch {}
}

// Helper: add or update history entry with the latest correct title
function addOrUpdateHistory(title) {
    const page = new URLSearchParams(window.location.search).get('page');
    if (!page) return;
    const url = window.location.href;
    try {
        let history = JSON.parse(localStorage.getItem('pageHistory') || '[]');
        // Remove any existing entry for this page
        history = history.filter(h => h.page !== page);
        history.unshift({ page, title, url, timestamp: Date.now() });
        history = history.slice(0, 20);
        localStorage.setItem('pageHistory', JSON.stringify(history));
    } catch {}
}

// Modal System
function showBookmarksModal() {
    const modal = createModal('Bookmarks', getBookmarksHTML());
    document.body.appendChild(modal);
    modal.classList.add('show');
}

function showHistoryModal() {
    const modal = createModal('Recent Pages', getHistoryHTML());
    document.body.appendChild(modal);
    modal.classList.add('show');
}

function createModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal">
            <div class="modal-header">
                <h3>${title}</h3>
                <button class="modal-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-content">${content}</div>
        </div>
    `;
    
    modal.querySelector('.modal-close').addEventListener('click', () => closeModal(modal));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal(modal);
    });
    
    return modal;
}

function closeModal(modal) {
    modal.classList.remove('show');
    setTimeout(() => {
        if (modal.parentNode) modal.parentNode.removeChild(modal);
    }, 300);
}

function getBookmarksHTML() {
    try {
        const bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
        if (bookmarks.length === 0) {
            return '<div class="empty-state"><i class="fas fa-bookmark"></i><p>No bookmarks yet</p></div>';
        }
        
        return bookmarks.map(bookmark => `
            <div class="bookmark-item">
                <div class="bookmark-content">
                    <a href="${bookmark.url}" class="bookmark-title">${bookmark.title}</a>
                    <div class="bookmark-meta">
                        <span class="bookmark-date">${new Date(bookmark.timestamp).toLocaleDateString()}</span>
                        ${bookmark.progress ? `<span class="bookmark-progress">${bookmark.progress}% read</span>` : ''}
                    </div>
                </div>
                <button class="bookmark-remove" onclick="removeBookmarkFromModal('${bookmark.url}', this)" title="Remove bookmark">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
    } catch {
        return '<div class="empty-state"><p>Error loading bookmarks</p></div>';
    }
}

function getHistoryHTML() {
    try {
        const history = JSON.parse(localStorage.getItem('pageHistory') || '[]');
        if (history.length === 0) {
            return '<div class="empty-state"><i class="fas fa-history"></i><p>No recent pages</p></div>';
        }
        
        return history.map(item => `
            <div class="history-item">
                <a href="${item.url}" class="history-title">${item.title}</a>
                <div class="history-date">${new Date(item.timestamp).toLocaleString()}</div>
            </div>
        `).join('');
    } catch {
        return '<div class="empty-state"><p>Error loading history</p></div>';
    }
}

window.removeBookmarkFromModal = function(url, button) {
    removeBookmark(url);
    button.closest('.bookmark-item').remove();
    updateBookmarkButton();
    showNotification('Bookmark removed', 'success');
};

function shareCurrentPage() {
    const url = window.location.href;
    const title = document.title;
    
    if (navigator.share) {
        navigator.share({ title, url });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link copied to clipboard', 'success');
        }).catch(() => {
            showNotification('Could not copy link', 'error');
        });
    }
}

function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 10);
    
    const autoHide = setTimeout(() => hideNotification(notification), duration);
    
    notification.querySelector('.notification-close').addEventListener('click', () => {
        clearTimeout(autoHide);
        hideNotification(notification);
    });
}

function hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

function restorePreferences() {
    const tocVisible = localStorage.getItem('tocVisible');
    if (tocVisible === 'false') {
        const toc = document.getElementById('tableOfContents');
        if (toc) {
            toc.style.display = 'none';
            const toggleBtn = document.getElementById('tocToggle');
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                const text = toggleBtn.childNodes[toggleBtn.childNodes.length - 1];
                if (icon) icon.className = 'fas fa-list-ul';
                if (text) text.textContent = ' Show Contents';
                toggleBtn.title = 'Show table of contents';
            }
        }
    }
}

// Keyboard shortcuts enhancement
document.addEventListener('keydown', (e) => {
    const cmd = e.metaKey || e.ctrlKey;
    
    // F1 for help - show keyboard shortcuts
    if (e.key === 'F1') {
        e.preventDefault();
        showKeyboardShortcuts();
    }
    
    // Ctrl/Cmd + K for quick search
    if (cmd && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('navigationSearch');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    
    // Ctrl/Cmd + D for bookmark toggle
    if (cmd && e.key === 'd') {
        e.preventDefault();
        const bookmarkBtn = document.getElementById('bookmarkBtn');
        if (bookmarkBtn) {
            bookmarkBtn.click();
        }
    }
    
    // Ctrl/Cmd + P for print (override default to show notification)
    if (cmd && e.key === 'p') {
        showNotification('Printing tutorial...', 'info');
        // Let default print behavior continue
    }
    
    // ESC to close modals or menus
    if (e.key === 'Escape') {
        // Close modals
        const modal = document.querySelector('.modal-overlay.show');
        if (modal) {
            closeModal(modal);
            return;
        }
        
        // Close FAB menu
        const fab = document.querySelector('.quick-actions-fab.open');
        if (fab) {
            fab.classList.remove('open');
            fab.querySelector('.fab-main i').className = 'fas fa-plus';
            return;
        }
        
        // Clear search
        const searchInput = document.getElementById('navigationSearch');
        if (searchInput && searchInput.value) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            return;
        }
    }
});

function showKeyboardShortcuts() {
    const shortcuts = [
        // Browser Find-in-page tips (cross‑platform) — prioritized at top
        { key: 'Ctrl/⌘ + F', description: 'Find in page (browser)' },
        { key: '⌘ + G (mac), F3 / Ctrl + G (Win/Linux)', description: 'Find next (browser)' },
        { key: 'Shift + ⌘ + G (mac), Shift + F3 / Ctrl + Shift + G (Win/Linux)', description: 'Find previous (browser)'},
        { key: 'F1', description: 'Show keyboard shortcuts' },
        { key: 'Ctrl/⌘ + K', description: 'Focus sidebar search' },
        { key: 'Ctrl/⌘ + D', description: 'Toggle bookmark' },
        { key: 'Ctrl/⌘ + P', description: 'Print tutorial' },
        { key: 'Ctrl/⌘ + B', description: 'Toggle sidebar width' },
        { key: 'Ctrl/⌘ + H', description: 'Go to home' },
        { key: '+/-', description: 'Zoom in/out' },
        { key: 'Esc', description: 'Close dialogs/clear search' }
    ];
    
    const shortcutsHTML = `
        <div class="shortcuts-grid">
            ${shortcuts.map(shortcut => `
                <div class="shortcut-item">
                    <kbd>${shortcut.key}</kbd>
                    <span>${shortcut.description}</span>
                </div>
            `).join('')}
        </div>
    `;
    
    const modal = createModal('Keyboard Shortcuts', shortcutsHTML);
    document.body.appendChild(modal);
    modal.classList.add('show');
}
