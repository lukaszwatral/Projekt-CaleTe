document.addEventListener('DOMContentLoaded', function() {
    var advancedFilters = document.getElementById('advanced-filters');
    var toggleButton = document.getElementById('toggle-advanced-filters');

    // Check the state from localStorage and set the display accordingly
    if (localStorage.getItem('advancedFiltersVisible') === 'true') {
        advancedFilters.style.display = 'block';
    } else {
        advancedFilters.style.display = 'none';
    }

    toggleButton.addEventListener('click', function() {
        if (advancedFilters.style.display === 'none') {
            advancedFilters.style.display = 'block';
            localStorage.setItem('advancedFiltersVisible', 'true');
        } else {
            advancedFilters.style.display = 'none';
            localStorage.setItem('advancedFiltersVisible', 'false');
        }
    });
});