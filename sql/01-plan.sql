-- Do czyszczenia bazy jak kiedyś coś nie zadziała
DROP TABLE IF EXISTS Department;
DROP TABLE IF EXISTS RoomBuilding;
DROP TABLE IF EXISTS StudyCourse;
DROP TABLE IF EXISTS Subject;
DROP TABLE IF EXISTS StudyGroup;
DROP TABLE IF EXISTS Student;
DROP TABLE IF EXISTS GroupStudent;
DROP TABLE IF EXISTS Teacher;
DROP TABLE IF EXISTS Lesson;
DROP TABLE IF EXISTS CourseStudent;

CREATE TABLE Department (
     id INTEGER PRIMARY KEY AUTOINCREMENT,
     name TEXT NOT NULL,
     shortName TEXT NOT NULL
);

CREATE TABLE RoomBuilding (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    buildingRoom TEXT NOT NULL,
    departmentId INTEGER NOT NULL,
    FOREIGN KEY (departmentId) REFERENCES Department(id)
);

CREATE TABLE StudyCourse (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tokName TEXT NOT NULL,
    shortType TEXT NOT NULL, --licencjackie, magisterskie, doktoranckie
    shortKind TEXT NOT NULL, --stacjonarne, niestacjonarne
    specialisation TEXT,
    major TEXT NOT NULL
);

CREATE TABLE Subject (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    form TEXT NOT NULL, --wykład, ćwiczenia, laboratoria
--     opis TEXT NOT NULL,
    studyCourseId INTEGER NOT NULL,
    FOREIGN KEY (studyCourseId) REFERENCES StudyCourse(id)
);

CREATE TABLE StudyGroup (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

CREATE TABLE Student (
    id INTEGER PRIMARY KEY --id to numer albumu
);

CREATE TABLE GroupStudent(
    groupId INTEGER NOT NULL,
    studentId INTEGER NOT NULL,
    FOREIGN KEY (groupId) REFERENCES StudyGroup(id),
    FOREIGN KEY (studentId) REFERENCES Student(id)
);

CREATE TABLE Teacher (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    firstName TEXT NOT NULL,
    lastName TEXT NOT NULL,
    title TEXT NOT NULL
--     zastepca text,
);

CREATE TABLE Lesson(
    id INTEGER PRIMARY KEY,
--     opis
    dateStart TEXT NOT NULL,
    dateEnd TEXT NOT NULL,
    teacherCover TEXT,
    semester INTEGER NOT NULL,
    teacherId INTEGER NOT NULL,
    departmentId INTEGER NOT NULL,
    groupId INTEGER NOT NULL,
    studyCourseId INTEGER NOT NULL,
    subjectId INTEGER NOT NULL,
    roomId INTEGER NOT NULL,
    FOREIGN KEY (teacherId) REFERENCES Teacher(id),
    FOREIGN KEY (departmentId) REFERENCES Department(id),
    FOREIGN KEY (groupId) REFERENCES StudyGroup(id),
    FOREIGN KEY (studyCourseId) REFERENCES StudyCourse(id),
    FOREIGN KEY (subjectId) REFERENCES Subject(id),
    FOREIGN KEY (roomId) REFERENCES RoomBuilding(id)
);

CREATE TABLE CourseStudent(
    courseId INTEGER NOT NULL,
    studentId INTEGER NOT NULL,
    FOREIGN KEY (courseId) REFERENCES StudyCourse(id),
    FOREIGN KEY (studentId) REFERENCES Student(id)
);