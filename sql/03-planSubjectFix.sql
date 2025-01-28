drop table Subject;

CREATE TABLE Subject (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    form TEXT NOT NULL, --wykład, ćwiczenia, laboratoria
    shortForm TEXT NOT NULL,
    studyCourseId INTEGER NOT NULL,
    FOREIGN KEY (studyCourseId) REFERENCES StudyCourse(id)
);