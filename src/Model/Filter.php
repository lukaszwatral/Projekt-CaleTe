<?php
//namespace App\Model;
//
//use App\Service\Config;
//use App\Service\Scrape;
//class Filter {
//    private ?int $id = null;
//    private ?string $dateStart = null;
//    private ?string $dateEnd = null;
//    private ?string $teacherCover = null;
//    private ?int $teacherId = null;
//    private ?int $departmentId = null;
//    private ?int $studyGroupId = null;
//    private ?int $studyCourseId = null;
//    private ?int $subjectId = null;
//    private ?int $classroomId = null;
//    private ?int $semester = null;
//    private ?int $yearOfStudy = null;
//    private ?int $studentId = null;
//    private ?string $teacherName = null;
//    private ?string $subjectName = null;
//    private ?string $classroomName = null;
//    private ?string $studyGroupName = null;
//    private ?string $subjectForm = null;
//    private ?string $departmentName = null;
//
//    private ?string $studyCourseName = null;
//
//    private ?string $major = null;
//    private ?string $specialisation = null;
//
//    public function getId(): ?int
//    {
//    return $this->id;
//    }
//
//    public function setId(?int $id): void
//    {
//        $this->id = $id;
//    }
//
//    public function getDateStart(): ?string
//    {
//        return $this->dateStart;
//    }
//
//    public function setDateStart(?string $dateStart): void
//    {
//        $this->dateStart = $dateStart;
//    }
//
//    public function getDateEnd(): ?string
//    {
//        return $this->dateEnd;
//    }
//
//    public function setDateEnd(?string $dateEnd): void
//    {
//        $this->dateEnd = $dateEnd;
//    }
//
//    public function getTeacherCover(): ?string
//    {
//        return $this->teacherCover;
//    }
//
//    public function setTeacherCover(?string $teacherCover): void
//    {
//        $this->teacherCover = $teacherCover;
//    }
//
//    public function getTeacherId(): ?int
//    {
//        return $this->teacherId;
//    }
//
//    public function setTeacherId(?int $teacherId): void
//    {
//        $this->teacherId = $teacherId;
//    }
//
//    public function getDepartmentId(): ?int
//    {
//        return $this->departmentId;
//    }
//
//    public function setDepartmentId(?int $departmentId): void
//    {
//        $this->departmentId = $departmentId;
//    }
//
//    public function getStudyGroupId(): ?int
//    {
//        return $this->studyGroupId;
//    }
//
//    public function setStudyGroupId(?int $studyGroupId): void
//    {
//        $this->studyGroupId = $studyGroupId;
//    }
//
//    public function getStudyCourseId(): ?int
//    {
//        return $this->studyCourseId;
//    }
//
//    public function setStudyCourseId(?int $studyCourseId): void
//    {
//        $this->studyCourseId = $studyCourseId;
//    }
//
//    public function getSubjectId(): ?int
//    {
//        return $this->subjectId;
//    }
//
//    public function setSubjectId(?int $subjectId): void
//    {
//        $this->subjectId = $subjectId;
//    }
//
//    public function getClassroomId(): ?int
//    {
//        return $this->classroomId;
//    }
//
//    public function setClassroomId(?int $classroomId): void
//    {
//        $this->classroomId = $classroomId;
//    }
//
//    public function getSemester(): ?int
//    {
//        return $this->semester;
//    }
//
//    public function setSemester(?int $semester): void
//    {
//        $this->semester = $semester;
//    }
//
//    public function getYearOfStudy(): ?int
//    {
//        return $this->yearOfStudy;
//    }
//
//    public function setYearOfStudy(?int $yearOfStudy): void
//    {
//        $this->yearOfStudy = $yearOfStudy;
//    }
//
//    public function getStudentId(): ?int
//    {
//        return $this->studentId;
//    }
//
//    public function setStudentId(?int $studentId): void
//    {
//        $this->studentId = $studentId;
//    }
//
//    public function getTeacherName(): ?string
//    {
//        return $this->teacherName;
//    }
//
//    public function setTeacherName(?string $teacherName): void
//    {
//        $this->teacherName = $teacherName;
//    }
//
//    public function getSubjectName(): ?string
//    {
//        return $this->subjectName;
//    }
//
//    public function setSubjectName(?string $subjectName): void
//    {
//        $this->subjectName = $subjectName;
//    }
//
//    public function getClassroomName(): ?string
//    {
//        return $this->classroomName;
//    }
//
//    public function setClassroomName(?string $classroomName): void
//    {
//        $this->classroomName = $classroomName;
//    }
//
//    public function getStudyGroupName(): ?string
//    {
//        return $this->studyGroupName;
//    }
//
//    public function setStudyGroupName(?string $studyGroupName): void
//    {
//        $this->studyGroupName = $studyGroupName;
//    }
//
//    public function getSubjectForm(): ?string
//    {
//        return $this->subjectForm;
//    }
//
//    public function setSubjectForm(?string $subjectForm): void
//    {
//        $this->subjectForm = $subjectForm;
//    }
//
//    public function getDepartmentName(): ?string
//    {
//        return $this->departmentName;
//    }
//
//    public function setDepartmentName(?string $departmentName): void
//    {
//        $this->departmentName = $departmentName;
//    }
//
//    public function getStudyCourseName(): ?string
//    {
//        return $this->studyCourseName;
//    }
//
//    public function setStudyCourseName(?string $studyCourseName): void
//    {
//        $this->studyCourseName = $studyCourseName;
//    }
//
//    public function getMajor(): ?string
//    {
//        return $this->major;
//    }
//
//    public function setMajor(?string $major): void
//    {
//        $this->major = $major;
//    }
//
//    public function getSpecialisation(): ?string
//    {
//        return $this->specialisation;
//    }
//
//    public function setSpecialisation(?string $specialisation): void
//    {
//        $this->specialisation = $specialisation;
//    }
//
//    public static function fromArray($array): Filter
//    {
//        $filter = new self();
//        $filter->fill($array);
//
//        return $filter;
//    }
//
//    public function fill($array): Filter
//    {
//        if (isset($array['id']) && ! $this->getId()) {
//            $this->setId($array['id']);
//        }
//        if (isset($array['dateStart'])) {
//            $this->setDateStart($array['dateStart']);
//        }
//        if (isset($array['dateEnd'])) {
//            $this->setDateEnd($array['dateEnd']);
//        }
//        if (isset($array['teacherCover'])) {
//            $this->setTeacherCover($array['teacherCover']);
//        }
//        if (isset($array['teacherId'])) {
//            $this->setTeacherId($array['teacherId']);
//        }
//        if (isset($array['departmentId'])) {
//            $this->setDepartmentId($array['departmentId']);
//        }
//        if (isset($array['studyGroupId'])) {
//            $this->setStudyGroupId($array['studyGroupId']);
//        }
//        if (isset($array['studyCourseId'])) {
//            $this->setStudyCourseId($array['studyCourseId']);
//        }
//        if (isset($array['subjectId'])) {
//            $this->setSubjectId($array['subjectId']);
//        }
//        if (isset($array['classroomId'])) {
//            $this->setClassroomId($array['classroomId']);
//        }
//        if (isset($array['semester'])) {
//            $this->setSemester($array['semester']);
//            $this->setYearOfStudy(ceil($array['semester'] / 2));
//        }
//        if (isset($array['studentId'])) {
//            $this->setStudentId($array['studentId']);
//        }
//        if (isset($array['teacherName'])) {
//            $this->setTeacherName($array['teacherName']);
//        }
//        if (isset($array['subjectName'])) {
//            $this->setSubjectName($array['subjectName']);
//        }
//        if (isset($array['classroomName'])) {
//            $this->setClassroomName($array['classroomName']);
//        }
//        if (isset($array['studyGroupName'])) {
//            $this->setStudyGroupName($array['studyGroupName']);
//        }
//        if (isset($array['departmentName'])) {
//            $this->setDepartmentName($array['departmentName']);
//        }
//        if (isset($array['studyCourseName'])) {
//            $this->setStudyCourseName($array['studyCourseName']);
//        }
//        if (isset($array['subjectForm'])) {
//            $this->setSubjectForm($array['subjectForm']);
//        }
//        if (isset($array['major'])) {
//            $this->setMajor($array['major']);
//        }
//        if (isset($array['specialisation'])) {
//            $this->setSpecialisation($array['specialisation']);
//        }
//
//        return $this;
//    }
//
//    public static function findAll(): array
//    {
//        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
//        $sql = 'SELECT * FROM Lesson';
//        $statement = $pdo->prepare($sql);
//        $statement->execute();
//
//        $lessons = [];
//        $lessonsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
//        foreach ($lessonsArray as $lessonArray) {
//            $lessons[] = self::fromArray($lessonArray);
//        }
//
//        return $lessons;
//    }
//
//    public static function find($id): ?Filter
//    {
//        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
//        $sql = 'SELECT * FROM Lesson WHERE id = :id';
//        $statement = $pdo->prepare($sql);
//        $statement->execute(['id' => $id]);
//
//        $lessonsArray = $statement->fetch(\PDO::FETCH_ASSOC);
//        if (! $lessonsArray) {
//            return null;
//        }
//        $lessons = Filter::fromArray($lessonsArray);
//
//        return $lessons;
//    }
//
//    public static function filteredFind($teacher = null, $subject = null, $classroom = null, $studyGroup = null, $department = null, $subjectForm = null, $studyCourse = null, $semester = null, $yearOfStudy = null, $student = null, $major = null, $specialisation = null): array
//    {
//        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
//
//        $sql = 'SELECT Lesson.*,
//                   (Teacher.firstName || " " || Teacher.lastName) AS teacherName,
//                   Subject.name AS subjectName,
//                   Subject.form AS subjectForm,
//                   RoomBuilding.buildingRoom AS classroomName,
//                   StudyGroup.name AS studyGroupName,
//                   Department.name AS departmentName,
//                   StudyCourse.tokName AS studyCourseName,
//                   StudyCourse.major AS major,
//                   StudyCourse.specialisation AS specialisation
//            FROM Lesson
//            LEFT JOIN Teacher ON Lesson.teacherId = Teacher.id
//            LEFT JOIN Subject ON Lesson.subjectId = Subject.id
//            LEFT JOIN RoomBuilding ON Lesson.roomId = RoomBuilding.id
//            LEFT JOIN StudyGroup ON Lesson.groupId = StudyGroup.id
//            LEFT JOIN Department ON Lesson.departmentId = Department.id
//            LEFT JOIN StudyCourse ON Lesson.studyCourseId = StudyCourse.id
//            WHERE 1=1';
//
//        $params = [];
//
//        if ($teacher != null) {
//            $teacherArray = explode(" ", $teacher);
//            $sql .= ' AND Teacher.firstName = :firstName AND Teacher.lastName = :lastName';
//            $params['firstName'] = $teacherArray[0];
//            $params['lastName'] = $teacherArray[1];
//        }
//        if ($subject != null) {
//            $sql .= ' AND Subject.name = :subject';
//            $params['subject'] = $subject;
//        }
//        if ($classroom != null) {
//            $sql .= ' AND RoomBuilding.buildingRoom = :classroom';
//            $params['classroom'] = $classroom;
//        }
//        if ($studyGroup != null) {
//            $sql .= ' AND StudyGroup.name = :studyGroup';
//            $params['studyGroup'] = $studyGroup;
//        }
//        if ($department != null) {
//            $sql .= ' AND Department.name = :department';
//            $params['department'] = $department;
//        }
//        if ($subjectForm != null) {
//            $sql .= ' AND Subject.form = :subjectForm';
//            $params['subjectForm'] = $subjectForm;
//        }
//        if ($studyCourse != null) {
//            $sql .= ' AND StudyCourse.tokName = :studyCourse';
//            $params['studyCourse'] = $studyCourse;
//        }
//        if ($semester != null) {
//            $sql .= ' AND Lesson.semester = :semester';
//            $params['semester'] = $semester;
//        }
//        if ($yearOfStudy != null) {
//            $sql .= ' AND (Lesson.semester = :yearOfStudy1 OR Lesson.semester = :yearOfStudy2)';
//            $params['yearOfStudy1'] = $yearOfStudy * 2 - 1;
//            $params['yearOfStudy2'] = $yearOfStudy * 2;
//        }
//        if ($major != null) {
//            $sql .= ' AND StudyCourse.major = :major';
//            $params['major'] = $major;
//        }
//        if($specialisation != null){
//            $sql .= ' AND StudyCourse.specialisation = :specialisation';
//            $params['specialisation'] = $specialisation;
//        }
//        if($student != null){
//            $stmt = $pdo->prepare('SELECT id FROM Student WHERE id = :student');
//            $stmt->bindParam(':student', $student);
//            $stmt->execute();
//            $result = $stmt->fetchAll();
//            if($result){
//                $stmt = $pdo->prepare('SELECT groupId FROM GroupStudent WHERE studentId = :student');
//                $stmt->bindParam(':student', $student);
//                $stmt->execute();
//                $resultGroup = $stmt->fetchAll();
//                if($resultGroup){
//                    $sql .= ' AND (';
//                    $i = 0;
//                    foreach($resultGroup as $row){
//                        if($i > 0){
//                            $sql .= ' OR ';
//                        }
//                        $sql .= 'Lesson.groupId = :groupId' . $i;
//                        $params['groupId' . $i] = $row['groupId'];
//                        $i++;
//                    }
//                    $sql .= ')';
//                }
//                $stmt = $pdo->prepare('SELECT courseId FROM CourseStudent WHERE studentId = :student');
//                $stmt->bindParam(':student', $student);
//                $stmt->execute();
//                $resultCourse = $stmt->fetchAll();
//                if($resultCourse){
//                    $sql .= ' AND (';
//                    $i = 0;
//                    foreach($resultCourse as $row){
//                        if($i > 0){
//                            $sql .= ' OR ';
//                        }
//                        $sql .= 'Lesson.studyCourseId = :courseId' . $i;
//                        $params['courseId' . $i] = $row['courseId'];
//                        $i++;
//                    }
//                    $sql .= ')';
//                }
//            }
//            else{
//                $scrapeData = new Scrape($pdo);
//                $scrapeData->insertGroupStudent($student);
//                $stmt = $pdo->prepare('SELECT groupId FROM GroupStudent WHERE studentId = :student');
//                $stmt->bindParam(':student', $student);
//                $stmt->execute();
//                $resultGroup = $stmt->fetchAll();
//                if($resultGroup){
//                    $sql .= ' AND (';
//                    $i = 0;
//                    foreach($resultGroup as $row){
//                        if($i > 0){
//                            $sql .= ' OR ';
//                        }
//                        $sql .= 'Lesson.groupId = :groupId' . $i;
//                        $params['groupId' . $i] = $row['groupId'];
//                        $i++;
//                    }
//                    $sql .= ')';
//                }
//                $stmt = $pdo->prepare('SELECT courseId FROM CourseStudent WHERE studentId = :student');
//                $stmt->bindParam(':student', $student);
//                $stmt->execute();
//                $resultCourse = $stmt->fetchAll();
//                if($resultCourse){
//                    $sql .= ' AND (';
//                    $i = 0;
//                    foreach($resultCourse as $row){
//                        if($i > 0){
//                            $sql .= ' OR ';
//                        }
//                        $sql .= 'Lesson.studyCourseId = :courseId' . $i;
//                        $params['courseId' . $i] = $row['courseId'];
//                        $i++;
//                    }
//                    $sql .= ')';
//                }
//            }
//        }
//        $statement = $pdo->prepare($sql);
//        $statement->execute($params);
//        $filteredLessonsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
//
//        $filteredLessons = [];
//        foreach ($filteredLessonsArray as $filteredLessonArray) {
//            $filteredLessons[] = self::fromArray($filteredLessonArray);
//        }
//
//        $mostFrequentSemester = self::getMostFrequentSemester($filteredLessons);
//        $filteredLessons = array_filter($filteredLessons, function($lesson) use ($mostFrequentSemester) {
//            return $lesson->getSemester() === $mostFrequentSemester;
//        });
//
//        return $filteredLessons;
//    }
//
//
//    public static function getMostFrequentSemester(array $lessons): ?int {
//        $semesterCounts = array_count_values(array_map(function($lesson) {
//            return $lesson->getSemester();
//        }, $lessons));
//
//        arsort($semesterCounts);
//        return key($semesterCounts);
//    }
//}