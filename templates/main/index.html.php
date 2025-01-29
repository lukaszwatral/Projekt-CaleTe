<?php

/** @var \App\Model\Lesson[] $filteredLessons */
/** @var \App\Service\Router $router */

$title = 'Plan Całe Te';
$bodyClass = 'index';

ob_start(); ?>


    <div id="historyPanel" class="history-panel">
        <button type="button" id="closeHistoryBtn" class="close-btn">&times;</button>
        <h3>Ostatnie wyszukiwania</h3>
        <ul id="historyList">
            <?php if (isset($_COOKIE['searchHistory'])):
                $history = json_decode($_COOKIE['searchHistory'], true); ?>
                <?php foreach ($history as $item): ?>
                <li class="history-item" data-search="<?= htmlspecialchars(json_encode($item)) ?>">
                    <?= htmlspecialchars(implode(', ', $item)) ?>
                </li>
            <?php endforeach; ?>
            <?php else: ?>
                <li>Brak zapisanej historii.</li>
            <?php endif; ?>
        </ul>

        <button type="button" id="clearHistoryBtn" class="btn">Usuń historię</button>
    </div>

    <div class="history">
        <button type="button" id="historyBtn" class="btn">Historia</button>
    </div>

    <div id="filterForm">
        <div class="filterContainer">
            <form action="<?= $router->generatePath('main-index') ?>" method="get">
                <label for="teacher">Wykładowca:</label><br>
                <input type="text" id="teacher" name="teacher" value="<?= htmlspecialchars($_GET['teacher'] ?? '') ?>"><br>
                <div id="teacherSuggestions" class="suggestions"></div>

                <label for="classroom">Sala:</label><br>
                <input type="text" id="classroom" name="classroom" value="<?= htmlspecialchars($_GET['classroom'] ?? '') ?>"><br>
                <div id="classroomSuggestions" class="suggestions"></div>

                <label for="subject">Przedmiot:</label><br>
                <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($_GET['subject'] ?? '') ?>"><br>
                <div id="subjectSuggestions" class="suggestions"></div>

                <label for="studyGroup">Grupa:</label><br>
                <input type="text" id="studyGroup" name="studyGroup" value="<?= htmlspecialchars($_GET['studyGroup'] ?? '') ?>"><br>
                <div id="studyGroupSuggestions" class="suggestions"></div>

                <label for="student">Numer albumu:</label><br>
                <input type="text" id="student" name="student" value="<?= htmlspecialchars($_GET['student'] ?? '') ?>"><br>
                <div id="studentSuggestions" class="suggestions"></div>

                <div id="advanced-filters" style="display: none;">
                    <label for="department">Wydział:</label><br>
                    <input type="text" id="department" name="department" value="<?= htmlspecialchars($_GET['department'] ?? '') ?>"><br>
                    <div id="departmentSuggestions" class="suggestions"></div>

                    <label for="subjectForm">Forma przedmiotu:</label><br>
                    <input type="text" id="subjectForm" name="subjectForm" value="<?= htmlspecialchars($_GET['subjectForm'] ?? '') ?>"><br>

                    <label for="studyCourse">Tok studiów:</label><br>
                    <input type="text" id="studyCourse" name="studyCourse" value="<?= htmlspecialchars($_GET['studyCourse'] ?? '') ?>"><br>
                    <div id="studyCourseSuggestions" class="suggestions"></div>

                    <label for="semester">Semestr:</label><br>
                    <input type="text" id="semester" name="semester" value="<?= htmlspecialchars($_GET['semester'] ?? '') ?>"><br>

                    <label for="yearOfStudy">Rok studiów:</label><br>
                    <input type="text" id="yearOfStudy" name="yearOfStudy" value="<?= htmlspecialchars($_GET['yearOfStudy'] ?? '') ?>"><br>

                    <label for="major">Kierunek:</label><br>
                    <input type="text" id="major" name="major" value="<?= htmlspecialchars($_GET['major'] ?? '') ?>"><br>

                    <label for="specialisation">Specjalizacja:</label><br>
                    <input type="text" id="specialisation" name="specialisation" value="<?= htmlspecialchars($_GET['specialisation'] ?? '') ?>"><br>
                </div>

                <div class="button-container">
                    <button type="button" id="toggle-advanced-filters" class="btn">Filtry zaawansowane</button>
                    <button type="button" id="search-btn" class="btn">Szukaj</button>
                    <button type="button" id="reset-filters" class="btn">Wyczyść filtry</button>
                </div>
            </form>
        </div>
    </div>
    <script src="/assets/scripts/filter.js"></script>
    <script src="/assets/scripts/buttons.js"></script>



    <!--    <ul class="index-list">-->
    <!--        --><?php //if (empty($filteredLessons)): ?>
    <!--            <li>No results found.</li>-->
    <!--        --><?php //else: ?>
    <!--            --><?php //foreach ($filteredLessons as $filteredLesson): ?>
    <!--                <li><h3>--><?php //= $filteredLesson->getId(), ". " , $filteredLesson->getDateStart(), "-", $filteredLesson->getDateEnd(), ", <br>Prowadzący: ", $filteredLesson->getTeacherName(), ", <br>Przedmiot: ", $filteredLesson->getSubjectName(), ", Forma: ", $filteredLesson->getSubjectForm(), ", <br>Sala: ", $filteredLesson->getClassroomName(), ", Grupa: ", $filteredLesson->getStudyGroupName(), ", <br>Wydział: ", $filteredLesson->getDepartmentName(), ", Tok: ", $filteredLesson->getStudyCourseName(), ", sem: ", $filteredLesson->getSemester(), ", rok: ", $filteredLesson->getYearOfStudy(), ", <br>Kierunek: ", $filteredLesson->getMajor(), ", Specjalizacja: ", $filteredLesson->getSpecialisation() ?><!--</h3>-->
    <!--                </li>-->
    <!--            --><?php //endforeach; ?>
    <!--        --><?php //endif; ?>
    <!--    </ul>-->


    <!--    <div class="button-container">-->
    <!--        <button type="button" id="toggle-view-btn">Zmiana zakresu wyświetlania</button>-->
    <!--        <button type="button" id="calendar-format-btn">Zmiana sposobu wyświetlania</button>-->
    <!--    </div>-->

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <div id="calendar"></div>
    <div id="stats">
        <p id="event-count"></p>
    </div>
    <div id="legend">
        <h2>Legenda</h2>
        <ul class="legend-list">
            <li><span class="color-box" style="background-color: #1A8238;"></span> laboratorium</li>
            <li><span class="color-box" style="background-color: #247C84;"></span> wykład</li>
            <li><span class="color-box" style="background-color: #C44F00;"></span> lektorat</li>
            <li><span class="color-box" style="background-color: #555500;"></span> projekt</li>
            <li><span class="color-box" style="background-color: #007BB0;"></span> audytoryjne</li>
            <li><span class="color-box" style="background-color: #004ca8;"></span> egzamin</li>
            <li><span class="color-box" style="background-color: #494949;"></span> odwołane</li>
        </ul>

    </div>
    <!--    <script src="https://unpkg.com/tippy.js@6"></script>-->
    <script src="/assets/scripts/calendar.js"></script>
    <script src="/assets/scripts/historypanel.js"></script>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';