document.addEventListener('DOMContentLoaded', function () {
    const changeFontBtn = document.getElementById('change-font-btn');
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-filters');
    const filterForm = document.querySelector('form[action*="main-index"]');

    // Check the state from localStorage and set the display accordingly
    if (localStorage.getItem('isFontLarge') === 'true') {
        document.body.classList.add('large-font');
    }

    if (localStorage.getItem('isDarkMode') === 'true') {
        document.body.classList.add('dark-mode');
        darkModeBtn.textContent = 'Jasny motyw';
    }

    changeFontBtn.addEventListener('click', () => {
        if (document.body.classList.contains('large-font')) {
            document.body.classList.remove('large-font');
            localStorage.setItem('isFontLarge', 'false');
        } else {
            document.body.classList.add('large-font');
            localStorage.setItem('isFontLarge', 'true');
        }
    });

    darkModeBtn.addEventListener('click', () => {
        if (document.body.classList.contains('dark-mode')) {
            document.body.classList.remove('dark-mode');
            darkModeBtn.textContent = 'Ciemny motyw';
            localStorage.setItem('isDarkMode', 'false');
        } else {
            document.body.classList.add('dark-mode');
            darkModeBtn.textContent = 'Jasny motyw';
            localStorage.setItem('isDarkMode', 'true');
        }
    });

    searchBtn.addEventListener('click', () => {
        filterForm.submit();
    });

    resetBtn.addEventListener('click', () => {
        window.location.href = '/';
    });
});