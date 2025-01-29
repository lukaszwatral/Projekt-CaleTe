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

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) === 0) {
            try {
                return JSON.parse(c.substring(nameEQ.length, c.length));
            } catch (error) {
                console.error("Błąd parsowania cookies:", error);
                return [];
            }
        }
    }
    return [];
}

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + JSON.stringify(value) + "; path=/" + expires;
}

function getSearchFilters() {
    let filters = {};
    let inputs = document.querySelectorAll("#filterForm input");

    inputs.forEach(input => {
        if (input.value.trim() !== "") {
            filters[input.name] = input.value.trim();
        }
    });

    return filters;
}

function saveSearch() {
    let filters = getSearchFilters();

    if (Object.keys(filters).length === 0) {
        console.warn("Nie zapisano historii – brak wprowadzonych danych.");
        return;
    }

    let searchHistory = getCookie("searchHistory") || [];
    searchHistory.unshift(filters);

    if (searchHistory.length > 10) {
        searchHistory = searchHistory.slice(0, 10);
    }

    setCookie("searchHistory", searchHistory, 7);
    displaySearchHistory();
}


function displaySearchHistory() {
    let history = getCookie("searchHistory") || [];
    let historyList = document.getElementById("historyList");

    if (!historyList) {
        console.error("Nie znaleziono elementu #historyList");
        return;
    }

    historyList.innerHTML = "";

    history.forEach(search => {
        let li = document.createElement("li");
        li.textContent = Object.entries(search)
            .map(([key, value]) => `${key}: ${value}`)
            .join(", ");
        historyList.appendChild(li);
    });
}

function clearHistory() {
    setCookie("searchHistory", [], -1);
    displaySearchHistory();
}

document.addEventListener("DOMContentLoaded", function () {
    const historyBtn = document.getElementById("historyBtn");
    const historyContent = document.getElementById("historyContent");
    const clearHistoryBtn = document.getElementById("clearHistoryBtn");

    if (historyBtn && historyContent) {
        historyBtn.addEventListener("click", function () {
            historyContent.style.display =
                historyContent.style.display === "none" || historyContent.style.display === ""
                    ? "block"
                    : "none";
            displaySearchHistory();
        });
    } else {
        console.error("Nie znaleziono #historyBtn lub #historyContent");
    }

    if (clearHistoryBtn) {
        clearHistoryBtn.addEventListener("click", function () {
            clearHistory();
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const searchBtn = document.getElementById("search-btn");
    const filterForm = document.querySelector("#filterForm form");

    if (searchBtn && filterForm) {
        searchBtn.addEventListener("click", function (event) {
            event.preventDefault();
            saveSearch();
        });
    } else {
        console.error("Nie znaleziono przycisku 'Szukaj' lub formularza.");
    }
});
