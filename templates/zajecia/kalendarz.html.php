<?php

/** @var \App\Service\Router $router */

$title = 'Kalendarz';
$bodyClass = 'kalendarz';

ob_start(); ?>
    <form action="<?= $router->generatePath("kalendarz-index")?>">

    </form>
    <h1>KALENDARZ</h1>
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

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                events: async function (fetchInfo, successCallback, failureCallback) {
                    const { startStr, endStr } = fetchInfo;
                    currentDates.start = startStr;
                    currentDates.end = endStr;

                    console.log(`Fetching events from ${startStr} to ${endStr}`);  // Debugging

                    await fetchEvents(startStr, endStr);

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
        });
    </script>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
