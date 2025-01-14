<?php

/** @var \App\Model\Zajecia[] $zajecia */
/** @var \App\Service\Router $router */

$title = 'Zajecia';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Zajecia List</h1>

    <form action="<?= $router->generatePath('zajecia-index') ?>" method="get">
        <label for="wykladowca">Wykladowca:</label>
        <input type="text" id="wykladowca" name="wykladowca" value="<?= htmlspecialchars($_GET['wykladowca'] ?? '') ?>">

        <label for="przedmiot">Przedmiot:</label>
        <input type="text" id="przedmiot" name="przedmiot" value="<?= htmlspecialchars($_GET['przedmiot'] ?? '') ?>">

        <label for="sala">Sala:</label>
        <input type="text" id="sala" name="sala" value="<?= htmlspecialchars($_GET['sala'] ?? '') ?>">

        <label for="grupa">Grupa:</label>
        <input type="text" id="grupa" name="grupa" value="<?= htmlspecialchars($_GET['grupa'] ?? '') ?>">

        <label for="wydzial">Wydzial:</label>
        <input type="text" id="wydzial" name="wydzial" value="<?= htmlspecialchars($_GET['wydzial'] ?? '') ?>">

        <label for="forma_przedmiotu">Forma Przedmiotu:</label>
        <input type="text" id="forma_przedmiotu" name="forma_przedmiotu" value="<?= htmlspecialchars($_GET['forma_przedmiotu'] ?? '') ?>">

        <label for"typ_studiow">Typ Studiow:</label>
        <input type="text" id="typ_studiow" name="typ_studiow" value="<?= htmlspecialchars($_GET['typ_studiow'] ?? '') ?>">

        <label for="semestr_studiow">Semestr Studiow:</label>
        <input type="text" id="semestr_studiow" name="semestr_studiow" value="<?= htmlspecialchars($_GET['semestr_studiow'] ?? '') ?>">

        <label for="rok_studiow">Rok Studiow:</label>
        <input type="text" id="rok_studiow" name="rok_studiow" value="<?= htmlspecialchars($_GET['rok_studiow'] ?? '') ?>">

        <label for="student">Student:</label>
        <input type="text" id="student" name="student" value="<?= htmlspecialchars($_GET['student'] ?? '') ?>">

        <button type="submit">Filter</button>
    </form>

    <div class="button-container">
        <button type="button" id="search-btn">Szukaj</button>
        <button type="button" id="reset-filters">Wyczyść filtry</button>
    </div>

    <ul class="index-list">
        <?php if (empty($zajecia)): ?>
            <li>No results found.</li>
        <?php else: ?>
            <?php foreach ($zajecia as $zaj): ?>
                <li><h3><?= $zaj->getId(), ". " , $zaj->getDataStart(), "-", $zaj->getDataKoniec(), ", <br>Prowadzący: ", $zaj->getWykladowcaName(), ", <br>Przedmiot: ", $zaj->getPrzedmiotName(), ", Forma: ", $zaj->getFormaPrzedmiotu(), ", <br>Sala: ", $zaj->getSalaName(), ", Grupa: ", $zaj->getGrupaName(), ", <br>Wydział: ", $zaj->getWydzialName(), ", typ: ", $zaj->getTypStudiowName(), ", sem: ", $zaj->getSemestr(), ", rok: ", $zaj->getRokStudiow() ?></h3>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h1>KALENDARZ</h1>

    <div class="button-container">
        <button type="button" id="toggle-view-btn">Zmiana zakresu wyświetlania</button>
        <button type="button" id="calendar-format-btn">Zmiana sposobu wyświetlania</button>
    </div>

    <form id="filter-form" style="display: none;">
        <?php include __DIR__ . '/../filter/_form.html.php'; ?>
    </form>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <!-- Include FullCalendar CSS from node_modules -->
    <link href="../../node_modules/@fullcalendar/main.min.css" rel="stylesheet" />
    <!-- Include tippy.js CSS -->
    <link href="https://unpkg.com/tippy.js@6/dist/tippy.css" rel="stylesheet" />
    <!-- Create a container for the calendar -->
    <div id="calendar"></div>
    <!-- Include FullCalendar JS from node_modules -->
    <script src="../../node_modules/@fullcalendar/main.min.js"></script>
    <!-- Include tippy.js -->
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var events = [];
            var currentDates = { start: '', end: '' };

            async function fetchEvents(startDate, endDate) {
                const url = `templates\zajecia\get_events.php?start=${startDate}&end=${endDate}`;
                console.log(`Fetching events from ${startDate} to ${endDate}`);  // Debugging
                try {
                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Failed to fetch events. Status: ' + response.status);

                    const data = await response.json();
                    console.log('Fetched events:', data);

                    events = data.map(event => ({
                        title: event.title,
                        start: event.start,
                        end: event.end,
                        description: event.description,
                        color: event.color,
                        extendedProps: event.extendedProps
                    }));

                } catch (error) {
                    console.error('Error fetching events:', error);
                }
            }

            var customEvent = {
                title: 'Custom Event',
                start: '2025-01-07T10:15:00',
                end: '2025-01-07T12:00:00',
                description: 'This is a custom event',
                color: '#ff0000' // Optional: set a custom color for the event
            };
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                events: async function (fetchInfo, successCallback, failureCallback) {
                    const { startStr, endStr } = fetchInfo;
                    currentDates.start = startStr;
                    currentDates.end = endStr;

                    console.log(`Fetching events from ${startStr} to ${endStr}`);  // Debugging

                    await fetchEvents(startStr, endStr);
                    events.push(customEvent);
                    if (events.length > 0) {
                        successCallback(events);
                    } else {
                        failureCallback('No events fetched');
                    }
                },
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
                    right: 'timeGridWeek,dayGridMonth,timeGridDay',
                },
                locale: 'pl',
                allDaySlot: false,
                datesSet: (dateInfo) => {
                    console.log(`Date range changed: ${dateInfo.startStr} to ${dateInfo.endStr}`);
                    currentDates.start = dateInfo.startStr;
                    currentDates.end = dateInfo.endStr;
                    fetchEvents(dateInfo.startStr, dateInfo.endStr);  // Fetch events on date range change
                },

            });

            calendar.render();

            const searchBtn = document.getElementById('search-btn');
            const resetBtn = document.getElementById('reset-filters');
            const filterForm = document.querySelector('form[action*="zajecia-index"]');

            searchBtn.addEventListener('click', () => {
                filterForm.submit();
            });

            resetBtn.addEventListener('click', () => {
                const inputs = filterForm.querySelectorAll('input');
                inputs.forEach(input => input.value = '');
                filterForm.submit();
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
    </script>

    
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';