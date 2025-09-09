import '../css/style.css';

// View Transitions Logic
// Intercept link clicks to trigger view transitions
document.addEventListener('click', (e) => {
    const anchor = e.target.closest('a');

    // Only intercept internal links and if browser supports API
    if (anchor && anchor.href.startsWith(window.location.origin) && document.startViewTransition) {
        e.preventDefault();
        const url = anchor.href;

        document.startViewTransition(async () => {
            const response = await fetch(url);
            const text = await response.text();

            // Extract body content (naive implementation, can be robustified)
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            const newBody = doc.body.innerHTML;
            const newTitle = doc.title;

            document.body.innerHTML = newBody;
            document.title = newTitle;

            // Update URL
            window.history.pushState({}, '', url);
        });
    }
});
