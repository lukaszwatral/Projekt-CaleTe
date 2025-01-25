document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var eventsCache = {};
    var customEvent = [
        {
            title: 'Sieci komputerowe (L)',
            start: '2025-01-07T10:15:00',
            end: '2025-01-07T12:00:00',
            description: 'Laboratorium z sieci komputerowych',
            color: '#006b27',
            extendedProps: {
                teacher: 'Jan Kowalski',
                room: 'B-123',
                subject: 'Sieci komputerowe',
                studyGroup: 'Informatyka',
            }
        },
        {
            title: 'Aplikacje internetowe (W)',
            start: '2025-01-07T12:15:00',
            end: '2025-01-07T14:00:00',
            description: 'Wykład z aplikacji internetowych',
            color: '#00809f'
        },
        {
            title: 'Język Angielski (Lek)',
            start: '2025-01-08T08:15:00',
            end: '2025-01-08T10:00:00',
            description: 'Lektorat z języka angielskiego',
            color: '#ef9529'
        },
        {
            title: 'IPZ (P)',
            start: '2025-01-08T10:15:00',
            end: '2025-01-08T12:00:00',
            description: 'Projekt z IPZ',
            color: '#5a6e02'
        },
        {
            title: 'WF (A)',
            start: '2025-01-09T12:15:00',
            end: '2025-01-09T14:00:00',
            description: 'Zajęcia audytoryjne z WF',
            color: '#004ca8'
        }
    ];



    async function fetchEvents(startDate, endDate) {
        const cacheKey = `${startDate}_${endDate}`;
        if (eventsCache[cacheKey]) {
            return eventsCache[cacheKey];
        }

        console.log(`Fetching events from ${startDate} to ${endDate}`);
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Failed to fetch events. Status: ' + response.status);

            const data = await response.json();
            console.log('Fetched events:', data);

            const events = data.map(event => ({
                title: event.title,
                start: event.start,
                end: event.end,
                description: event.description,
                color: event.color,
                extendedProps: event.extendedProps
            }));

            eventsCache[cacheKey] = events;
            return events;
        } catch (error) {
            console.error('Error fetching events:', error);
            return [];
        }
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        firstDay: 1,
        events: '../../index.php?action=event',
        loading: function (isLoading) {
            if (isLoading) {
                console.log('Loading events...');
            } else {
                console.log('Events loaded.');
            }
        },
        editable: false,
        eventLimit: true,
        slotMinTime: "07:00:00",
        slotMaxTime: "21:00:00",
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,dayGridMonth,timeGridDay,multiMonthYear',
        },
        views: {
            multiMonthYear: {
                type: 'dayGrid',
                start: '2025-01-01',
                end: '2025-12-31',
                buttonText: 'semester'
            }
        },
        responsive: {

            0: {
                initialView: 'listWeek',
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'listDay,listWeek',
                },
            },
            768: {
                initialView: 'timeGridWeek',
            },
        },
        windowResize: function (view) {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
            } else {
                calendar.changeView('timeGridWeek');
            }
        },
        locale: 'pl',
        allDaySlot: false,
        // datesSet: (dateInfo) => {
        //     console.log(`Date range changed: ${dateInfo.startStr} to ${dateInfo.endStr}`);
        // },
    });

    calendar.render();

    const changeFontBtn = document.getElementById('change-font-btn');
    let isFontLarge = false;

    changeFontBtn.addEventListener('click', () => {
        if (isFontLarge) {
            document.body.classList.remove('large-font');
            isFontLarge = false;
        } else {
            document.body.classList.add('large-font');
            isFontLarge = true;
        }
    });

    const darkModeBtn = document.getElementById('dark-mode-btn');
    let isDarkMode = false;

    darkModeBtn.addEventListener('click', () => {
        if (isDarkMode) {
            document.body.classList.remove('dark-mode');
            darkModeBtn.textContent = 'Ciemny motyw';
            isDarkMode = false;
        } else {
            document.body.classList.add('dark-mode');
            darkModeBtn.textContent = 'Jasny motyw';
            isDarkMode = true;
        }
    });

    const searchBtn = document.getElementById('search-btn');
    const filterForm = document.querySelector('form[action*="main-index"]');

    searchBtn.addEventListener('click', () => {
        filterForm.submit();
    });

    const resetBtn = document.getElementById('reset-filters');

    resetBtn.addEventListener('click', () => {
        window.location.href = '/'
    });

    document.getElementById('toggle-view-btn').addEventListener('click', () => {
        const views = ['timeGridWeek', 'dayGridMonth', 'listWeek'];
        const currentView = calendar.view.type;
        const nextView = views[(views.indexOf(currentView) + 1) % views.length];
        calendar.changeView(nextView);
    });

    document.getElementById('calendar-format-btn').addEventListener('click', () => {
        const views = ['timeGridWeek', 'timeGridDay', 'listWeek'];
        const currentView = calendar.view.type;
        const nextView = views[(views.indexOf(currentView) + 1) % views.length];
        calendar.changeView(nextView);
    });
});