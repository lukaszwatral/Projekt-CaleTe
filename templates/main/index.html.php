<?php

/** @var \App\Model\Filter[] $filteredLessons */
/** @var \App\Service\Router $router */

$title = 'Filter';
$bodyClass = 'index';

ob_start(); ?>

    <div id="filterForm">
        <form action="<?= $router->generatePath('main-index') ?>" method="get">
            <label for="teacher">Wykladowca:</label><br>
            <input type="text" id="teacher" name="teacher" value="<?= htmlspecialchars($_GET['teacher'] ?? '') ?>"><br>
            <label for="room">Sala:</label><br>
            <input type="text" id="room" name="room" value="<?= htmlspecialchars($_GET['classroom'] ?? '') ?>"><br>
            <label for="subject">Przedmiot:</label><br>
            <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($_GET['subject'] ?? '') ?>"><br>
            <label for="studyGroup">Grupa:</label><br>
            <input type="text" id="studyGroup" name="studyGroup" value="<?= htmlspecialchars($_GET['studyGroup'] ?? '') ?>"><br>
            <label for="student">Numer albumu:</label><br>
            <input type="text" id="student" name="student" value="<?= htmlspecialchars($_GET['student'] ?? '') ?>"><br>

            <div id="advanced-filters" style="display: none;">
                <label for="department">Wydzial:</label><br>
                <input type="text" id="department" name="department" value="<?= htmlspecialchars($_GET['department'] ?? '') ?>"><br>
                <label for="subjectForm">Forma przedmiotu:</label><br>
                <input type="text" id="subjectForm" name="subjectForm" value="<?= htmlspecialchars($_GET['subjectForm'] ?? '') ?>"><br>
                <label for="studyCourse">Typ studiow:</label><br>
                <input type="text" id="studyCourse" name="studyCourse" value="<?= htmlspecialchars($_GET['studyCourse'] ?? '') ?>"><br>
                <label for="semester">Semestr:</label><br>
                <input type="text" id="semester" name="semester" value="<?= htmlspecialchars($_GET['semester'] ?? '') ?>"><br>
                <label for="yearOfStudy">Rok studiow:</label><br>
                <input type="text" id="yearOfStudy" name="yearOfStudy" value="<?= htmlspecialchars($_GET['yearOfStudy'] ?? '') ?>"><br>
                <label for="major">Kierunek:</label><br>
                <input type="text" id="major" name="major" value="<?= htmlspecialchars($_GET['major'] ?? '') ?>"><br>
                <label for="specialisation">Specjalizacja:</label><br>
                <input type="text" id="specialisation" name="specialisation" value="<?= htmlspecialchars($_GET['specialisation'] ?? '') ?>"><br>
            </div>

            <button type="button" id="toggle-advanced-filters" class="btn">Filtry zaawansowane</button>
            <button type="submit" value="Submit" class="btn">Filter</button>
        </form>
        <div class="button-container">
            <button type="button" id="search-btn" class="btn">Szukaj</button>
            <button type="button" id="reset-filters" class="btn">Wyczyść filtry</button>
        </div>
    </div>
    <script src="/public/assets/scripts/filter.js"></script>



<!--    <ul class="index-list">-->
<!--        --><?php //if (empty($filteredLessons)): ?>
<!--            <li>No results found.</li>-->
<!--        --><?php //else: ?>
<!--            --><?php //foreach ($filteredLessons as $filteredLesson): ?>
<!--                <li><h3>--><?php //= $filteredLesson->getId(), ". " , $filteredLesson->getDateStart(), "-", $filteredLesson->getDateEnd(), ", <br>Prowadzący: ", $filteredLesson->getTeacherName(), ", <br>Przedmiot: ", $filteredLesson->getSubjectName(), ", Forma: ", $filteredLesson->getSubjectForm(), ", <br>Sala: ", $filteredLesson->getClassroomName(), ", Grupa: ", $filteredLesson->getStudyCourseName(), ", <br>Wydział: ", $filteredLesson->getDepartmentName(), ", Tok: ", $filteredLesson->getStudyCourseId(), ", sem: ", $filteredLesson->getSemester(), ", rok: ", $filteredLesson->getYearOfStudy(), ", <br>Kierunek: ", $filteredLesson->getMajor(), ", Specjalizacja: ", $filteredLesson->getSpecialisation() ?><!--</h3>-->
<!--                </li>-->
<!--            --><?php //endforeach; ?>
<!--        --><?php //endif; ?>
<!--    </ul>-->


<!--    <div class="button-container">-->
<!--        <button type="button" id="toggle-view-btn">Zmiana zakresu wyświetlania</button>-->
<!--        <button type="button" id="calendar-format-btn">Zmiana sposobu wyświetlania</button>-->
<!--    </div>-->

    <form id="filter-form" style="display: none;">
        <?php include __DIR__ . '/../filter/_form.html.php'; ?>
    </form>

    <link href="/public/assets/src/less/_calendar.css" rel="stylesheet" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <!-- Include FullCalendar CSS from node_modules -->
    <link href="../../node_modules/@fullcalendar/main.min.css" rel="stylesheet" />
    <!-- Include tippy.js CSS -->
    <link href="https://unpkg.com/tippy.js@6/dist/tippy.css" rel="stylesheet" />
    <!-- Create a container for the calendar -->
    <div id="calendar"></div>
    <div id="legend">
        <h2>Legenda</h2>
        <h3>Placeholder</h3>
    </div>
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
                console.log(`Fetching events from ${startDate} to ${endDate}`);
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
                color: '#ff0000'
            };

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                firstDay: 1,
                initialDate: '2025-10-01',
                events: async function (fetchInfo, successCallback, failureCallback) {
                    const { startStr, endStr } = fetchInfo;
                    currentDates.start = startStr;
                    currentDates.end = endStr;

                    console.log(`Fetching events from ${startStr} to ${endStr}`);

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
                    right: 'timeGridWeek,dayGridMonth,timeGridDay,halfYearView',
                },
                views: {
                    halfYearView: {
                        type: 'dayGrid',
                        duration: { months: 6 },
                        buttonText: 'semester'
                    }
                },
                locale: 'pl',
                allDaySlot: false,
                datesSet: (dateInfo) => {
                    console.log(`Date range changed: ${dateInfo.startStr} to ${dateInfo.endStr}`);
                    currentDates.start = dateInfo.startStr;
                    currentDates.end = dateInfo.endStr;
                    fetchEvents(dateInfo.startStr, dateInfo.endStr);
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