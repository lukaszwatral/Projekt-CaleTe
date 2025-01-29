document.addEventListener('DOMContentLoaded', function() {
    var advancedFilters = document.getElementById('advanced-filters');
    var toggleButton = document.getElementById('toggle-advanced-filters');

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

function setupAutocomplete(inputId, kind) {
    const inputElement = document.getElementById(inputId);
    const suggestions = document.getElementById(inputId + 'Suggestions');

    // Disable browser autocomplete
    inputElement.setAttribute('autocomplete', 'off');

    inputElement.addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 2) {
            fetch(`http://localhost:8000/index.php?action=apiplan2&kind=${kind}&query=${query}`)
                .then(response => response.json())
                .then(data => {
                    console.log('API Response:', data); // Debugging: Log the API response
                    suggestions.innerHTML = '';

                    // Remove duplicates
                    const uniqueData = Array.from(new Set(data.map(item => item.item)))
                        .map(item => {
                            return data.find(i => i.item === item);
                        });

                    if (uniqueData.length > 0) {
                        uniqueData.forEach(item => {
                            const div = document.createElement('div');
                            div.textContent = item.item; // Correctly reference the item property
                            div.addEventListener('click', function() {
                                inputElement.value = item.item; // Auto-fill the input field
                                suggestions.innerHTML = ''; // Clear suggestions
                                suggestions.style.display = 'none'; // Hide suggestions container
                            });
                            suggestions.appendChild(div);
                        });
                        suggestions.style.display = 'block'; // Show suggestions container
                    } else {
                        suggestions.style.display = 'none'; // Hide suggestions container if no results
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error); // Debugging: Log any errors
                });
        } else {
            suggestions.innerHTML = ''; // Clear suggestions if query length is less than 2
            suggestions.style.display = 'none'; // Hide suggestions container
        }
    });

    // inputElement.addEventListener('blur', function() {
    //     suggestions.style.display = 'none'; // Hide suggestions when input loses focus
    // });

    inputElement.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            suggestions.style.display = 'none'; // Hide suggestions when ESC key is pressed
        }
    });
}

setupAutocomplete('teacher', 'teacher');
setupAutocomplete('classroom', 'classroom');
setupAutocomplete('subject', 'subject');
setupAutocomplete('studyGroup', 'studygroup');
setupAutocomplete('department', 'department');
setupAutocomplete('studyCourse', 'studycourse');