document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('toggle-advanced-filters').addEventListener('click', function() {
        var advancedFilters = document.getElementById('advanced-filters');
        if (advancedFilters.style.display === 'none') {
            advancedFilters.style.display = 'block';
        } else {
            advancedFilters.style.display = 'none';
        }
    });
});