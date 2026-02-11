function applyTheme() {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
}

// Azonnal lefut, amint betölt az oldal
document.addEventListener('DOMContentLoaded', applyTheme);

// Ezt hívd meg a gombbal
function toggleTheme() {
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'light');
    } else {
        localStorage.setItem('theme', 'dark');
    }
    applyTheme();
}