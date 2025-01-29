document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var eventsCache = {};
    var eventCount = document.getElementById('event-count');
    var teacher = document.getElementById('teacher');
    var classroom = document.getElementById('classroom');
    var subject = document.getElementById('subject');
    var studyGroup = document.getElementById('studyGroup');
    var department = document.getElementById('department');
    var subjectForm = document.getElementById('subjectForm');
    var studyCourse = document.getElementById('studyCourse');
    var semester = document.getElementById('semester');
    var yearOfStudy = document.getElementById('yearOfStudy');
    var major = document.getElementById('major');
    var specialisation = document.getElementById('specialisation');
    var student = document.getElementById('student');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        firstDay: 1,

        events: function(fetchInfo, successCallback, failureCallback) {
            if (!teacher.value && !classroom.value && !subject.value && !studyGroup.value && !department.value && !subjectForm.value && !studyCourse.value && !semester.value && !yearOfStudy.value && !major.value && !specialisation.value && !student.value) {
                successCallback([]);
                return;
            }
            var url = '../../index.php?action=apiplan&subject=' + subject.value +
                '&teacher=' + teacher.value +
                '&classroom=' + classroom.value +
                '&studyGroup=' + studyGroup.value +
                '&department=' + department.value +
                '&studyCourse=' + studyCourse.value +
                '&semester=' + semester.value +
                '&yearOfStudy=' + yearOfStudy.value +
                '&major=' + major.value +
                '&specialisation=' + specialisation.value +
                '&subjectForm=' + subjectForm.value;

            if (student.value) {
                url += '&student=' + student.value;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                    updateEventCount();
                })
                .catch(error => failureCallback(error));
        },
        loading: function (isLoading) {
        },
        eventMouseEnter: function(info) {
            var description = info.event.extendedProps.description || 'No description available';
            var eventDetails = `
                <div id="event-popup">
                    <p>${description}</p>
                </div>
            `;

            var eventPopup = document.createElement('div');
            eventPopup.setAttribute('class', 'event-popup');
            eventPopup.innerHTML = eventDetails;

            eventPopup.style.position = 'absolute';
            eventPopup.style.backgroundColor = 'yellow';
            eventPopup.style.width = '250px';
            eventPopup.style.height = 'auto';
            eventPopup.style.border = '1px solid black';
            eventPopup.style.borderRadius = '5px';
            eventPopup.style.padding = '10px';
            eventPopup.style.zIndex = 9999;

            var eventElement = info.el;
            var boundingRect = eventElement.getBoundingClientRect();

            eventPopup.style.top = (window.scrollY + boundingRect.top - eventPopup.offsetHeight - 110) + 'px';
            eventPopup.style.left = (window.scrollX + boundingRect.left) + 'px';

            eventPopup.id = 'event-popup';
            document.body.appendChild(eventPopup);
        },
        eventMouseLeave: function(info) {
            var eventPopup = document.getElementById('event-popup');
            if (eventPopup) {
                eventPopup.remove();
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
                buttonText: 'semestr'
            }
        },
        buttonText: {
            today: "dziś",
            month: "miesiąc",
            week: "tydzień",
            day: "dzień",
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
    });
    calendar.on('eventAdd', function() {
        updateEventCount();
    });

    calendar.render();

    function updateEventCount() {
        let eventsTotal = calendar.getEvents();
        let visibleEvents = eventsTotal.filter(event => {
            return event.start >= calendar.view.currentStart && event.start < calendar.view.currentEnd;
        });

        eventCount.innerText = "Liczba wyników: " + eventsTotal.length + " (wyświetlono: " + visibleEvents.length + ")";
    }
});