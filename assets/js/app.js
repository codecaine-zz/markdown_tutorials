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
        sidebar.classList.toggle('active');
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
});
